<template>
    <div class="container">
      <!-- Countdown Timer (Top Right) -->
      <div class="timer">
        <span>{{ timer }}</span>
      </div>
  
      <!-- Quiz Content: Question on Left, Responses on Right -->
      <div class="quiz-content">
        <!-- Question (Left Side) -->
        <div class="question">
          <p class="question-text"><b>{{ currentQuestion.text }}</b></p>
        </div>
  
        <!-- Responses (Right Side) -->
        <div class="options-container">
          <label
            v-for="(option, index) in currentQuestion.options"
            :key="index"
            class="option-item"
          >
            <input
              type="radio"
              :name="'question-' + currentQuestionIndex"
              :value="option"
              v-model="answers[currentQuestionIndex]"
              class="response-radio"
            />
            <span class="circle"></span>
            <span>{{ option }}</span>
          </label>
        </div>
      </div>
  
      <!-- Next Button (Bottom Right) -->
      <div class="button-container">
        <button @click="nextQuestion" class="btn btn-success">Next</button>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from "vue";
  
  export default {
    name: "QuizComponent",
    setup() {
      const questions = [
        {
          text: "Which option best describes your job role?",
          options: [
            "Small Business Owner or Employee",
            "Nonprofit Owner or Employee",
            "Journalist or Activist",
            "Other",
          ],
        },
        {
          text: "What is Vue.js?",
          options: ["A framework", "A library", "A language", "A tool"],
        },
        {
          text: "What is a Vue component?",
          options: ["HTML", "CSS", "JavaScript", "None of the above"],
        },
      ];
  
      const currentQuestionIndex = ref(0);
      const currentQuestion = ref(questions[currentQuestionIndex.value]);
      const answers = ref([]);
      const timer = ref(30);
      let interval;
  
      const nextQuestion = () => {
        if (answers.value[currentQuestionIndex.value] === undefined) {
          answers.value[currentQuestionIndex.value] = "No Answer";
        }
        if (currentQuestionIndex.value < questions.length - 1) {
          currentQuestionIndex.value++;
          currentQuestion.value = questions[currentQuestionIndex.value];
          resetTimer();
        }
      };
  
      const resetTimer = () => {
        clearInterval(interval);
        timer.value = 30;
        startTimer();
      };
  
      const startTimer = () => {
        interval = setInterval(() => {
          if (timer.value > 0) {
            timer.value--;
          } else {
            clearInterval(interval);
          }
        }, 1000);
      };
  
      onMounted(() => {
        startTimer();
      });
  
      return {
        currentQuestion,
        currentQuestionIndex,
        answers,
        nextQuestion,
        timer,
      };
    },
  };
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
  align-items: center; /* Aligns question and responses */
  gap: 30px; /* Adds space without pushing too far apart */
  margin-bottom: 20px;
}

  
.question {
  width: 45%; /* Prevents question from shrinking too much */
  font-size: 18px;
  font-weight: bold;
  color: white;
  text-align: left;
}

.options-container {
  width: 50%; /* Ensures responses stay aligned */
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
    background-color: #21bf73;
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
    background-color: #21bf73;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
  }
  
  .btn-success:hover {
    background-color: #1a9b5f;
  }
  
  .timer {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #21bf73;
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
  
    .options-container {
      width: 100%;
    }
  
    .button-container {
      justify-content: center;
      width: 100%;
    }
  }
  </style>
  