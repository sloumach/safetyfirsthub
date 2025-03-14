<template>
    <section class="exam-section">
        <div v-if="hasAttemptsExhausted" class="container">
            <div class="attempts-exhausted">
                <i class="fas fa-exclamation-circle warning-icon"></i>
                <h2>Exam Attempts Exhausted</h2>
                <p>You have used all your allowed attempts for this exam.</p>
                <button @click="$router.push('/dashboard/exams')" class="btn btn-primary return-btn">
                    Return to Exams
                </button>
            </div>
        </div>
        <div v-else-if="noQuestionsAvailable" class="container">
            <div class="attempts-exhausted">
                <i class="fas fa-exclamation-circle warning-icon"></i>
                <h2>No Questions Available</h2>
                <p>This exam currently has no questions available. Please try another exam.</p>
                <button @click="$router.push('/dashboard/exams')" class="btn btn-primary return-btn">
                    Return to Exams
                </button>
            </div>
        </div>
        <div v-else class="container">
            <!-- Timer -->
            <div class="timer">
                <span>{{ timer }}</span>
            </div>

            <!-- Quiz Content -->
            <div class="quiz-content">
                <!-- Question -->
                <div v-if="currentQuestion" class="question">
                    <div class="question-header">
                        <i class="fas fa-question-circle question-icon"></i>
                        <p class="question-text">
                            <b>{{ capitalizeFirstLetter(currentQuestion.text) }}</b>
                        </p>
                    </div>
                </div>

                <!-- Choices -->
                <div v-if="currentQuestion" class="options-container">
                    <label v-for="(option, index) in currentQuestion.choices" :key="option.id" class="option-item">
                        <input type="radio" :name="'question-' + currentQuestionIndex" :value="option.id"
                            v-model="userAnswers[currentQuestionIndex]" class="response-radio" />
                        <span class="circle"></span>
                        <span>{{ option.text }}</span>
                    </label>
                </div>
            </div>

            <!-- Next Button -->
            <div class="button-container">
                <button @click="nextQuestion" class="btn btn-success" :disabled="!userAnswers[currentQuestionIndex]">
                    Next
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted, onBeforeUnmount } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import Swal from "sweetalert2";
import PreventSecurity from '@/dashboard/Security/security.js'; // Import du module

const route = useRoute();
const router = useRouter();
const sessionId = ref(null);
const currentQuestion = ref(null);
const userAnswers = ref([]);
const isSubmitting = ref(false);
const hasAttemptsExhausted = ref(false);
const noQuestionsAvailable = ref(false);
let isActive = true;

// Add currentQuestionIndex ref
const currentQuestionIndex = ref(0);

// Add the capitalizeFirstLetter function
const capitalizeFirstLetter = (string) => {
    if (!string) return '';
    return string.charAt(0).toUpperCase() + string.slice(1);
};

// Add timer ref
const timer = ref(10);
let timerInterval = null;

// Add these security-related refs
const isExamSessionActive = ref(true);

// Modify handleTimeUp function to automatically submit and move to next question
const handleTimeUp = async () => {
    if (isSubmitting.value) return; // Prevent multiple submissions
    
    try {
        // If user selected an answer, use it. Otherwise, use null
        const selectedAnswer = userAnswers.value[currentQuestionIndex.value] || null;
        userAnswers.value[currentQuestionIndex.value] = selectedAnswer;
        
        // Submit answer and move to next question
        await submitAnswer();
    } catch (error) {
        console.error("Error in handleTimeUp:", error);
    }
};

// Modify startTimer to ensure timer is working properly
const startTimer = () => {
    timer.value = 10;
    clearInterval(timerInterval);
    
    timerInterval = setInterval(() => {
        if (timer.value > 0) {
            timer.value--;
        } else {
            clearInterval(timerInterval);
            handleTimeUp(); // This will now automatically submit and move to next question
        }
    }, 1000);
};

// 🎯 Récupérer la prochaine question
const fetchNextQuestion = async () => {
    if (!isActive) return;

    try {
     
        const response = await axios.get(`/exam/${sessionId.value}/next-question`);
        console.log("fetchNextQuestion", response.data)
        if (response.data.exam_completed) {
            await handleExamCompletion(response.data);
            return;
        }
        currentQuestion.value = response.data.question;
        currentQuestionIndex.value++;
        startTimer(); // Start timer for new question
    } catch (error) {
        console.error("Error fetching next question:", error);

        const errorMessage = error.response?.data?.error || 'There was an error loading the next question.';
        
        // Show error to user
        await Swal.fire({
            title: 'Error',
            text: errorMessage,
            icon: 'error',
            confirmButtonText: 'OK'
        });

        // If there's an error with the session, redirect to exams page
        if (error.response?.status === 403) {
            router.push('/dashboard/exams');
        }
    }
};

// Add the nextQuestion function
const nextQuestion = async () => {
    try {
        // First submit the current answer
        await submitAnswer();
    } catch (error) {
        console.error("Error in nextQuestion:", error);
    }
};

// Modify submitAnswer function to handle timer properly
const submitAnswer = async () => {
    if (!isActive || isSubmitting.value) return;

    isSubmitting.value = true;
    clearInterval(timerInterval); // Clear the current timer
    const selectedChoice = userAnswers.value[currentQuestionIndex.value];

    try {
        const response = await axios.post(`/exam/${sessionId.value}/submit-answer`, {
            question_id: currentQuestion.value.id,
            choice_id: selectedChoice,
        });

   

        if (response.data.exam_completed) {
            if (response.data.role_changed) {
                window.location.href = '/courses';
            } else {
                await handleExamCompletion(response.data);
            }
            return;
        }

        // Only update question and increment index if submission was successful
        currentQuestion.value = response.data.question;
        currentQuestionIndex.value++;
        startTimer(); // Start timer for the new question
        
    } catch (error) {
        console.error("Error submitting answer:", error);
        
        // Show specific error message to user
        const errorMessage = error.response?.data?.error || 'There was an issue submitting your answer. Please try again.';
        
        await Swal.fire({
            title: 'Warning',
            text: errorMessage,
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        // If the question was already answered or there's another issue,
        // try to fetch the next question directly
        if (error.response?.status === 403) {
            await fetchNextQuestion();
        }
    } finally {
        isSubmitting.value = false;
    }
};

// Modify handleExamCompletion to ensure redirect after exam is done
const handleExamCompletion = async (examData) => {
    clearInterval(timerInterval); // Clear timer when exam is complete
    try {
        const { score, passing_score, retry_allowed, attempts_left, role_changed } = examData;
        console.log("attempts_left", attempts_left)
        const hasPassed = score >= 70; // Consider pass if score is 70% or higher
        const result = await Swal.fire({
            title: hasPassed ? "Félicitations ! 🎉" : "Examen échoué ❌",
            text: hasPassed
                ? `Vous avez réussi avec un score de ${score}% (Minimum requis : ${passing_score}%).`
                : `Votre score est de ${score}%. ${retry_allowed ? `Il vous reste ${attempts_left} tentatives.` : "Vous ne pouvez plus réessayer."}`,
            icon: hasPassed ? "success" : "error",
            confirmButtonColor: "#3085d6",
            confirmButtonText: hasPassed ? "Obtenir le certificat" : "OK",
            showCancelButton: hasPassed,
            cancelButtonText: hasPassed ? "Retour aux examens" : undefined,
        });

        if (hasPassed && result.isConfirmed) {
            // Redirect to certificate page            
            router.push("/dashboard/certificates");
            
        } else {       
                router.push("/dashboard/exams"); 
        }
    } catch (error) {
        console.error("Error handling exam completion:", error);
        router.push("/dashboard/exams"); // Ensure redirect even on error
    }
};

const reportSecurityBreach = async (reason) => {
                const response = await axios.post(`/exam/${sessionId.value}/complete`, { 
                    message: reason, 
                    timestamp: new Date().toISOString() 
                });
             
                await Swal.fire({
                    title: response.data.message,
                    icon: 'warning',
                    confirmButtonText: 'OK',
                });
            
};

// 🏁 Démarrer une session d'examen
onMounted(async () => {
    PreventSecurity.setSecurityCallback(reportSecurityBreach);
    PreventSecurity.init(router);
    try {
        const response = await axios.post(`/exam/start/${route.params.id}`);
        sessionId.value = response.data.session_id;
        fetchNextQuestion();
    } catch (error) { 
      
        // Handle max attempts reached error
        if (error.response?.status === 403 && error.response?.data?.error === 'exam.max_attempts_reached') {
            hasAttemptsExhausted.value = true;
            
            // Show alert before resetting progress
            await Swal.fire({
                title: 'Maximum Attempts Reached',
                text: 'You have reached the maximum number of attempts. Your progress will be reset.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
     
        }
    }
    
});

// 🛑 Nettoyage à la fermeture de la page
onUnmounted(() => {
    isActive = false;
    clearInterval(timerInterval);
});

// Clean up event listeners
onBeforeUnmount(() => {
    PreventSecurity.cleanup();

    
});
</script>



<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background-color: #333;
}

.exam-section {
    padding-top: 100px !important;
    padding-bottom: 100px !important;
}

@media (max-width: 576px) {
    .exam-section {
        padding-top: 105px !important;
        padding-bottom: 100px !important;
    }
}

.container {
    background-color: #555;
    color: #ddd;
    border-radius: 10px;
    padding: 20px;
    font-family: "Montserrat", sans-serif;
    max-width: 71%;
    margin: 50px auto;
    position: relative;
    display: flex;
    flex-direction: column;
}

.quiz-content {
    display: inline;
    align-items: center;
    gap: 30px;
    margin-bottom: 20px;
}

.question {
    width: 45%;
    font-size: 18px;
    font-weight: bold;
    color: white;
    text-align: left;
}

.options-container {
    width: 50%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.option-item {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    margin-bottom: 10px;
}

.response-radio {
    display: none;
}

.circle {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid white;
    display: inline-block;
    position: relative;
}

.response-radio:checked+.circle {
    background-color: #FF8A00;
}

.response-radio:checked+.circle::after {
    content: "";
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: white;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.button-container {
    display: flex;
    justify-content: flex-end;
    margin-top: auto;
}

.btn-success {
    padding: 8px 20px;
    background-color: #FF8A00 !important;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.btn-success:hover {
    background-color: #FF8A00 !important;
}

.timer {
    position: absolute;
    top: 10px;
    right: 30px;
    background-color: #FF8A00 !important;
    color: white;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    font-weight: bold;
}

@media (max-width: 576px) {
    .quiz-content {
        flex-direction: column;
        align-items: center;
    }

    .question {
        width: 100%;
        text-align: center;
        margin-bottom: 20px;
    }

    .timer {
        position: fixed;
        top: 10px;
        right: 10px;
        width: 40px;
        height: 40px;
        font-size: 16px;
    }

    .options-container {
        width: 100%;
    }

    .button-container {
        justify-content: center;
        width: 100%;
    }
}

.question-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
}

.question-icon {
    font-size: 24px;
    color: #FF8A00;
}

.question-text {
    font-size: 20px;
    color: white;
    margin: 0;
}

.options-container {
    margin-top: 30px;
    width: 50%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.attempts-exhausted {
    text-align: center;
    padding: 2rem;
}

.warning-icon {
    font-size: 3rem;
    color: #f0ad4e;
    margin-bottom: 1rem;
}

.attempts-exhausted h2 {
    color: #fff;
    margin-bottom: 1rem;
}

.attempts-exhausted p {
    color: #ddd;
    margin-bottom: 2rem;
}

.return-btn {
    padding: 8px 20px;
    background-color: #FF8A00 !important;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: opacity 0.3s ease;
}

.return-btn:hover {
    opacity: 0.9;
}
</style>
