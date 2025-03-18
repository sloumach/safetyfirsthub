<template>
    <div class="quiz-wrapper">
        <!-- Quiz Header -->
        <div class="quiz-header">
            <div class="quiz-title">
                <i class="fas fa-question-circle"></i>
                <h3>Section Quiz</h3>
            </div>
            <div class="quiz-info">
                <span class="passing-score">
                    <i class="fas fa-award"></i>
                    Passing Score: {{ quiz.passing_score }}%
                </span>
                <span class="timer">
                    <i class="fas fa-clock"></i>
                    Time left: {{ timer }}s
                </span>
            </div>
        </div>

        <!-- Quiz Questions -->
        <div class="quiz-content">
            <div v-if="currentQuestion" class="question-card">
                <div class="question-header">
                    <span class="question-number">Question {{ currentQuestionIndex + 1 }} of {{ quiz.questions.length }}</span>
                    <p class="question-text">{{ currentQuestion.text }}</p>
                </div>
                
                <div class="choices-container">
                    <label v-for="choice in currentQuestion.choices" 
                           :key="choice.id" 
                           class="choice-label"
                           :class="{ 'selected': selectedAnswers[currentQuestion.id] === choice.id }">
                        <input type="radio" 
                               :name="'question-' + currentQuestion.id"
                               :value="choice.id"
                               v-model="selectedAnswers[currentQuestion.id]"
                               class="choice-input">
                        <span class="choice-checkbox"></span>
                        <span class="choice-text">{{ choice.text }}</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress" :style="{ width: `${(currentQuestionIndex + 1) * 100 / quiz.questions.length}%` }"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import PreventSecurity from '../Security/security';
import { useRouter } from 'vue-router';

const props = defineProps({
    quiz: {
        type: Object,
        required: true
    },
    sectionId: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['quizCompleted']);

const selectedAnswers = ref({});
const currentQuestionIndex = ref(0);
const timer = ref(10);
let timerInterval = null;
const router = useRouter();
const isQuizActive = ref(true);
const currentContent = ref(null);

//hedhy initialisation with the first question
const currentQuestion = computed(() => {
    return props.quiz.questions[currentQuestionIndex.value];
});

const startTimer = () => {
    timer.value = 10;
    clearInterval(timerInterval);
    
    timerInterval = setInterval(() => {
        if (timer.value > 0) {
            timer.value--;
        } else {
            handleTimeUp();
        }
    }, 1000);
};

const handleTimeUp = () => {
    console.log('Time up for question:', {
        questionId: currentQuestion.value.id,
        questionText: currentQuestion.value.text,
        answer: 'No answer provided (time expired)'
    });
    clearInterval(timerInterval);
    moveToNextQuestion();
};

const moveToNextQuestion = async () => {
    console.log('Moving to next question:', {
        currentQuestionIndex: currentQuestionIndex.value,
        totalQuestions: props.quiz.questions.length,
        remainingQuestions: props.quiz.questions.length - (currentQuestionIndex.value + 1)
    });
    
    if (currentQuestionIndex.value === props.quiz.questions.length - 1) {
        await submitQuiz();
        return;
    }
    
    currentQuestionIndex.value++;
    startTimer();
};

const submitQuiz = async () => {
    clearInterval(timerInterval);
    
    try {
        const answers = props.quiz.questions.map(question => ({
            question_id: question.id,
            selected_choice_id: selectedAnswers.value[question.id] || null
        }));

        const response = await axios.post(`/sections/${props.sectionId}/quiz/submit`, {
            quiz_id: props.quiz.id,
            answers: answers
        });

        const { score, passed } = response.data;

        // First emit the result
        emit('quizCompleted', { passed, score });

        // Then show the alert
        await Swal.fire({
            title: passed ? 'Congratulations! 🎉' : 'Quiz Failed',
            text: `Your score: ${score}%. ${passed ? 'You can now proceed to the next section!' : 'Please try again.'}`,
            icon: passed ? 'success' : 'error',
            confirmButtonColor: '#FF8A00',
            allowOutsideClick: false,
            allowEscapeKey: false
        });

    } catch (error) {
        console.error('Quiz Submission Error:', error);
        
        Swal.fire({
            title: 'Error',
            text: 'Failed to submit quiz. Please try again.',
            icon: 'error',
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    }
};

// Watch for answer selection to automatically move to next question
watch(() => selectedAnswers.value[currentQuestion.value?.id], (newValue) => {
    if (newValue !== undefined) {
        console.log('Question answered:', {
            questionId: currentQuestion.value.id,
            questionText: currentQuestion.value.text,
            selectedAnswerId: newValue,
            selectedAnswerText: currentQuestion.value.choices.find(c => c.id === newValue)?.text
        });
        setTimeout(() => moveToNextQuestion(), 500);
    }
});

// Add this method to handle security violations
const handleSecurityViolation = async () => {
    if (isQuizActive.value) {
        isQuizActive.value = false;
        clearInterval(timerInterval);

        // Submit empty answers to force a fail
        const emptyAnswers = props.quiz.questions.map(question => ({
            question_id: question.id,
            selected_choice_id: null
        }));

        try {
            const response = await axios.post(`/sections/${props.sectionId}/quiz/submit`, {
                quiz_id: props.quiz.id,
                answers: emptyAnswers
            });

            await Swal.fire({
                title: 'Quiz Failed',
                text: 'Quiz submitted with score 0% due to security violation.',
                icon: 'error',
                confirmButtonColor: '#FF8A00'
            });

            emit('quizCompleted', { passed: false, score: 0 });
            router.push('/dashboard/courses');
        } catch (error) {
            console.error('Error submitting quiz:', error);
        }
    }
};

// Initialize security when component mounts
onMounted(() => {
    isQuizActive.value = true;
    currentContent.value = { type: 'quiz' };
    PreventSecurity.setSecurityCallback(handleSecurityViolation);
    // Use initVideo with isQuiz = true
    PreventSecurity.initVideo(
        router,
        null, // no video ref needed for quiz
        currentContent,
        ref(false), // completed ref
        true // isQuiz flag
    );

    document.addEventListener('visibilitychange', () => {
        if (document.hidden && isQuizActive.value) {
            handleSecurityViolation();
        }
    });

    window.addEventListener('blur', () => {
        if (isQuizActive.value) {
            handleSecurityViolation();
        }
    });

    startTimer();
});

// Cleanup when component unmounts
onUnmounted(() => {
    isQuizActive.value = false;
    PreventSecurity.cleanupVideo(); // Use cleanupVideo instead of cleanup
    document.removeEventListener('visibilitychange', handleSecurityViolation);
    window.removeEventListener('blur', handleSecurityViolation);
    clearInterval(timerInterval);
});
</script>

<style scoped>
.quiz-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 24px;
    margin: 20px 0;
}

.quiz-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f0f0f0;
}

.quiz-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.quiz-title i {
    color: #FF8A00;
    font-size: 24px;
}

.quiz-title h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 20px;
}

.quiz-info {
    color: #666;
    font-size: 14px;
}

.passing-score {
    display: flex;
    align-items: center;
    gap: 8px;
}

.passing-score i {
    color: #FF8A00;
}

.timer {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: 16px;
    font-weight: bold;
    color: #FF8A00;
}

.timer i {
    color: #FF8A00;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    margin-top: 24px;
    overflow: hidden;
}

.progress {
    height: 100%;
    background: #FF8A00;
    transition: width 0.3s ease;
}

.question-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    animation: fadeIn 0.3s ease;
}

.question-header {
    margin-bottom: 16px;
}

.question-number {
    display: inline-block;
    background: #FF8A00;
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 14px;
    margin-bottom: 8px;
}

.question-text {
    color: #2c3e50;
    font-size: 16px;
    margin: 8px 0;
    font-weight: 500;
}

.choices-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.choice-label {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.choice-label:hover {
    border-color: #FF8A00;
    background: #fff5e6;
}

.choice-label.selected {
    border-color: #FF8A00;
    background: #fff5e6;
}

.choice-input {
    display: none;
}

.choice-checkbox {
    width: 20px;
    height: 20px;
    border: 2px solid #FF8A00;
    border-radius: 50%;
    margin-right: 12px;
    position: relative;
    transition: all 0.3s ease;
}

.choice-label.selected .choice-checkbox::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 12px;
    height: 12px;
    background: #FF8A00;
    border-radius: 50%;
}

.choice-text {
    color: #2c3e50;
    font-size: 15px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.timer.low {
    color: #dc3545;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

@media (max-width: 768px) {
    .quiz-wrapper {
        padding: 16px;
    }

    .quiz-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .question-card {
        padding: 16px;
    }
    .timer {
   
    margin-left: 16px;
   
}
.passing-score{
    margin-left: 16px;
}
}
</style> 