<template>
  <div class="container">
    <!-- Timer -->
    <div class="timer">
      <span>{{ timer }}</span>
    </div>

    <!-- Quiz Content -->
    <div class="quiz-content">
      <!-- Question -->
      <div v-if="currentQuestion" class="question">
        <p class="question-text">
          <b>{{ currentQuestion.text }}</b>
        </p>
      </div>

      <!-- Choices -->
      <div v-if="currentQuestion" class="options-container">
        <label
          v-for="(option, index) in currentQuestion.choices"
          :key="option.id"
          class="option-item"
        >
          <input
            type="radio"
            :name="'question-' + currentQuestionIndex"
            :value="option.id"
            v-model="userAnswers[currentQuestionIndex]"
            class="response-radio"
          />
          <span class="circle"></span>
          <span>{{ option.text }}</span>
        </label>
      </div>
    </div>

    <!-- Next Button -->
    <div class="button-container">
      <button
        @click="nextQuestion"
        class="btn btn-success"
        :disabled="!userAnswers[currentQuestionIndex]"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";

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

// Fetch the next question
const fetchNextQuestion = async () => {
  if (!isActive) return; // Prevent execution if component is unmounted

  try {
    const response = await axios.get(`/exams/${sessionId.value}/question`, {
      cancelToken: axiosSource.token, // Attach cancel token
    });

    if (response.data.exam_completed) {
      calculateScore();
      return;
    }
    currentQuestion.value = response.data.question;
    startTimer();
  } catch (error) {
    if (axios.isCancel(error)) {
      console.log("Request canceled:", error.message);
    } else {
      console.error("Error fetching question:", error);
    }
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
  if (!isActive) return; // Prevent submitting after leaving

  const selectedChoice = userAnswers.value[currentQuestionIndex.value] ?? null;
  try {
    await axios.post(
      `/exams/${sessionId.value}/answer`,
      {
        question_id: currentQuestion.value.id,
        choice_id: selectedChoice,
      },
      { cancelToken: axiosSource.token }
    );

    currentQuestionIndex.value++;
    fetchNextQuestion();
  } catch (error) {
    if (axios.isCancel(error)) {
      console.log("Request canceled:", error.message);
    } else {
      console.error("Error submitting answer:", error);
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
      alert(`🎉 Félicitations ! Vous avez réussi l'examen avec un score de ${score}% (Minimum requis: ${passing_score}%)`);
    } else {
      alert(`❌ Échec. Votre score est de ${score}%, alors que le minimum requis est ${passing_score}%. Réessayez plus tard.`);
    }

    router.push("/dashboard/exams"); // Redirect after exam
  } catch (error) {
    if (axios.isCancel(error)) {
      console.log("Request canceled:", error.message);
    } else {
      console.error("Erreur lors de la récupération des résultats de l'examen:", error);
    }
  }
};

// Start Exam Session
onMounted(async () => {
  try {
    axiosSource = axios.CancelToken.source(); // Reset cancel token
    const response = await axios.post(`/exams/start/${route.params.id}`, null, {
      cancelToken: axiosSource.token,
    });

    sessionId.value = response.data.session_id;
    console.log(sessionId.value);
    fetchNextQuestion();
  } catch (error) {
    if (axios.isCancel(error)) {
      console.log("Request canceled:", error.message);
    } else {
      console.error("Error starting exam:", error);
    }
  }
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

.response-radio:checked + .circle {
  background-color: var(--main-color);
}

.response-radio:checked + .circle::after {
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
</style>
