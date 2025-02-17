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
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();

const sessionId = ref(null);
const currentQuestionIndex = ref(0);
const currentQuestion = ref(null);
const userAnswers = ref([]);
const timer = ref(10);
let timerInterval = null;
let isActive = true; // Prevents background API calls
let axiosSource = axios.CancelToken.source(); // Create a cancel token
const hasAttemptsExhausted = ref(false);
const noQuestionsAvailable = ref(false);

// Fetch the next question
const fetchNextQuestion = async () => {
    if (!isActive) return;

    try {
        const response = await axios.get(`/exams/${sessionId.value}/question`);

        if (response.data.exam_completed) {
            const score = response.data.score ?? 0;
            const passingScore = response.data.passing_score || 60;

            if (score >= passingScore) {
                await Swal.fire({
                    title: 'Congratulations! 🎉',
                    text: `You passed the exam with a score of ${score}% (Minimum required: ${passingScore}%)`,
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                });
            } else {
                if (response.data.retry_allowed) {
                    await Swal.fire({
                        title: 'Exam Failed',
                        text: `Your score is ${score}%. You need ${passingScore}% to pass. You have ${response.data.attempts_left || 0} attempts left.`,
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    await Swal.fire({
                        title: 'Exam Failed',
                        text: `Your score is ${score}%, while ${passingScore}% was required. You cannot retry.`,
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            }
            router.push("/dashboard/exams");
            return;
        }

        currentQuestion.value = response.data.question;
        startTimer();
    } catch (error) {
        console.error("Error fetching next question:", error);
    }
};


// Start Timer
const startTimer = () => {
    timer.value = 10;
    clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        if (!isActive) return; // Stop timer if user navigates away
        timer.value--;
        if (timer.value === 0) {
            submitAnswer();
        }
    }, 1000);
};

// Submit Answer
const submitAnswer = async () => {
    if (!isActive) return;

    const selectedChoice = userAnswers.value[currentQuestionIndex.value] ?? null;
    try {
        const answerResponse = await axios.post(
            `/exams/${sessionId.value}/answer`,
            {
                question_id: currentQuestion.value.id,
                choice_id: selectedChoice,
            },
            { cancelToken: axiosSource.token }
        );
        // Check if this is the last question
        const nextQuestionResponse = await axios.get(`/exams/${sessionId.value}/question`);

        if (nextQuestionResponse.data.exam_completed) {
            try {
                const resultsResponse = await axios.get(`/exams/${sessionId.value}/results`);
            
                const score = resultsResponse.data.score;
                const passingScore = resultsResponse.data.passing_score || 60;
                const attemptsLeft = resultsResponse.data.attempts_left || 0;

                if (score >= passingScore) {
                    await Swal.fire({
                        title: 'Congratulations! 🎉',
                        text: `You passed the exam with a score of ${score}% (Minimum required: ${passingScore}%)`,
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    if (resultsResponse.data.retry_allowed) {
                        await Swal.fire({
                            title: 'Exam Failed',
                            text: `Your score is ${score}%. You need ${passingScore}% to pass. You have ${attemptsLeft} attempts left.`,
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        await Swal.fire({
                            title: 'Exam Failed',
                            text: `Your score is ${score}%, while ${passingScore}% was required. You cannot retry.`,
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                }
                router.push("/dashboard/exams");
                return;
            } catch (resultsError) {
                console.error('Error fetching results:', resultsError);
            }
        } else {
            currentQuestionIndex.value++;
            currentQuestion.value = nextQuestionResponse.data.question;
            startTimer();
        }
    } catch (error) {
        if (axios.isCancel(error)) {
        
        } else {
            console.error("Error in submit answer flow:", error);
        }
    }
};

// "Next" Button Action
const nextQuestion = () => {
    submitAnswer();
};

// Calculate Score
const calculateScore = async () => {
    try {
        const response = await axios.get(`/exams/${sessionId.value}/results`, {
            cancelToken: axiosSource.token,
        });
        const { score, status, passing_score } = response.data;

        if (status === "passed") {
            await Swal.fire({
                title: 'Congratulations! 🎉',
                text: `Vous avez réussi l'examen avec un score de ${score}% (Minimum requis: ${passing_score}%)`,
                icon: 'success',
                confirmButtonColor: '#3085d6'
            });
        } else {
            await Swal.fire({
                title: 'Exam Failed ❌',
                text: `Votre score est de ${score}%, alors que le minimum requis est ${passing_score}%. Réessayez plus tard.`,
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        }

        router.push("/dashboard/exams"); // Redirect after exam
    } catch (error) {
        if (axios.isCancel(error)) {
            console.log("Request canceled:", error.message);
        } else {
            console.error("Erreur lors de la récupération des résultats de l'examen:", error);
        }
    }
};//

const handleTabChange = async () => {
  if (document.hidden) {
    // L'utilisateur quitte l'onglet => Marquer l'examen comme terminé
    if (sessionId.value) {
      try {
        await axios.post(`/exams/${sessionId.value}/complete`);
        await Swal.fire({
            title: 'Session Closed',
            text: 'Your exam session has been closed.',
            icon: 'warning',
            confirmButtonColor: '#3085d6'
        });
        router.push("/dashboard/exams"); // Redirection
      } catch (error) {
        console.error("Error marking exam as completed:", error);
      }
    }
  }
};

window.addEventListener("popstate", async () => {
  if (sessionId.value) {
    try {
      await axios.post(`/exams/${sessionId.value}/complete`);
      await Swal.fire({
          title: 'Session Closed',
          text: 'Exam session closed due to navigation attempt.',
          icon: 'warning',
          confirmButtonColor: '#3085d6'
      });
      router.push("/dashboard"); // Redirection forcée
    } catch (error) {
      console.error("Error marking exam as completed:", error);
    }
  }
});

window.addEventListener("blur", async () => {
  if (sessionId.value) {
    try {
      await axios.post(`/exams/${sessionId.value}/complete`);
      await Swal.fire({
          title: 'Session Closed',
          text: 'Exam session closed due to application switch.',
          icon: 'warning',
          confirmButtonColor: '#3085d6'
      });
      router.push("/dashboard/exams");
    } catch (error) {
      console.error("Error marking exam as completed:", error);
    }
  }
});

window.addEventListener("offline", async () => {
  if (sessionId.value) {
    try {
      await axios.post(`/exams/${sessionId.value}/complete`);
      await Swal.fire({
          title: 'Session Closed',
          text: 'Exam session closed due to internet disconnection.',
          icon: 'warning',
          confirmButtonColor: '#3085d6'
      });
      router.push("/dashboard/exams");
    } catch (error) {
      console.error("Error marking exam as completed:", error);
    }
  }
});

let inactivityTimer;

const resetInactivityTimer = () => {
  clearTimeout(inactivityTimer);
  inactivityTimer = setTimeout(async () => {
    if (sessionId.value) {
      try {
        await axios.post(`/exams/${sessionId.value}/complete`);
        await Swal.fire({
            title: 'Session Closed',
            text: 'Exam session closed due to inactivity.',
            icon: 'warning',
            confirmButtonColor: '#3085d6'
        });
        router.push("/dashboard/exams");
      } catch (error) {
        console.error("Error marking exam as completed:", error);
      }
    }
  }, 300000); // 5 minutes d'inactivité
};

// Détecter les mouvements et clics
window.addEventListener("mousemove", resetInactivityTimer);
window.addEventListener("keydown", resetInactivityTimer);
window.addEventListener("click", resetInactivityTimer);
resetInactivityTimer();


// Start Exam Session
onMounted(async () => {
    try {
        axiosSource = axios.CancelToken.source();
        const response = await axios.post(`/exams/start/${route.params.id}`, null, {
            cancelToken: axiosSource.token,
        });

        sessionId.value = response.data.session_id;

        fetchNextQuestion();
    } catch (error) {
        if (axios.isCancel(error)) {

        } else {
            if (error.response && error.response.status === 404) {
                noQuestionsAvailable.value = true;
            } else if (error.response && error.response.status === 403) {
                hasAttemptsExhausted.value = true;
            } else {
                console.error("Error starting exam:", error);
                await Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while starting the exam. Please try again later.',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                router.push("/dashboard/exams");
            }
        }
    }
    document.addEventListener("visibilitychange", handleTabChange);

    // Handle refresh attempts (Ctrl+R or F5)
    window.addEventListener('keydown', async (e) => {
        if ((e.ctrlKey && e.key === 'r') || e.key === 'F5') {
            if (sessionId.value) {
                await axios.post(`/exams/${sessionId.value}/complete`);
            }
            // Let the refresh happen naturally
            return true;
        }
    });
});

// Clean up when leaving the page
onUnmounted(() => {
    isActive = false; // Prevent any further actions
    clearInterval(timerInterval); // Stop timer
    axiosSource.cancel("Component unmounted, canceling requests"); // Cancel all pending API calls
    sessionId.value = null;
    currentQuestionIndex.value = 0;
    currentQuestion.value = null;
    userAnswers.value = [];
    document.removeEventListener("visibilitychange", handleTabChange);

});

// Add this function to capitalize first letter
const capitalizeFirstLetter = (string) => {
    if (!string) return '';
    return string.charAt(0).toUpperCase() + string.slice(1);
};

// Instead, add this to handle refresh cleanup
window.addEventListener('unload', async () => {
    if (sessionId.value) {
        // Using navigator.sendBeacon for more reliable cleanup during page unload
        navigator.sendBeacon(`/exams/${sessionId.value}/complete`);
    }
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
    background-color: var(--main-color);
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
    background-color: var(--main-color) !important;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.btn-success:hover {
    background-color: var(--main-color) !important;
}

.timer {
    position: absolute;
    top: 10px;
    right: 30px;
    background-color: var(--main-color) !important;
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
    color: var(--main-color);
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
    background-color: var(--main-color) !important;
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
