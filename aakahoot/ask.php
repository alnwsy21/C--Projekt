<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kahoot-Style Quiz Admin</title>
    <link rel="stylesheet" href="askstyle.css">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>

<body>

<div id="particles-js"></div>

<div id="quiz-container">
    <div id="quiz">
        <div id="question-container">
            <h2 id="question">Frage hier...</h2>
            <p id="question-counter">Frage <span id="current-question">0</span> von <span id="total-questions">0</span></p>
        </div>
        <div id="answers-container">
            <!-- Antworten werden hier eingefügt -->
        </div>
        <button id="reset-btn">Quiz zurücksetzen</button>
    </div>
    <div id="result">
        <!-- Ergebnisse werden hier angezeigt -->
    </div>
    <div id="leaderboard">
        <!-- Rangliste wird hier angezeigt -->
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const questionContainer = document.getElementById("question");
        const answersContainer = document.getElementById("answers-container");
        const resultContainer = document.getElementById("result");
        const resetButton = document.getElementById("reset-btn");
        const leaderboardContainer = document.getElementById("leaderboard");
        const currentQuestionElement = document.getElementById("current-question");
        const totalQuestionsElement = document.getElementById("total-questions");

        let currentQuestionIndex;
        let score;
        let questions;

        function loadQuestions() {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    questions = JSON.parse(xhr.responseText);
                    loadQuizState();
                }
            };
            xhr.open("GET", "get_questions.php", true);
            xhr.send();
        }

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }

        function loadQuizState() {
            currentQuestionIndex = parseInt(localStorage.getItem("currentQuestionIndex")) || 0;
            score = parseInt(localStorage.getItem("score")) || 0;

            shuffleArray(questions);

            if (currentQuestionIndex < questions.length) {
                showQuestion();
            } else {
                showResult();
            }
        }

        function saveQuizState() {
            localStorage.setItem("currentQuestionIndex", currentQuestionIndex.toString());
            localStorage.setItem("score", score.toString());
        }

        function showQuestion() {
            const currentQuestion = questions[currentQuestionIndex];
            questionContainer.textContent = currentQuestion.Frage;
            currentQuestionElement.textContent = (currentQuestionIndex + 1).toString();
            totalQuestionsElement.textContent = questions.length.toString();

            answersContainer.innerHTML = "";
            for (let i = 1; i <= 4; i++) {
                const answer = currentQuestion["Antwort" + i];
                const input = document.createElement("input");
                input.type = "radio";
                input.name = "answer";
                input.value = answer;
                input.required = true;

                const label = document.createElement("label");
                label.appendChild(input);
                label.appendChild(document.createTextNode(answer));

                label.addEventListener("click", checkAnswer);

                answersContainer.appendChild(label);
            }
        }

        function showResult() {
            const percentage = (score / questions.length) * 100;
            resultContainer.innerHTML = `<h2>Quiz beendet! Dein Ergebnis: ${score} von ${questions.length} (${percentage.toFixed(2)}%)</h2>`;

            if (percentage >= 80) {
                const playerName = prompt("Herzlichen Glückwunsch! Du hast die Top 10 erreicht. Bitte gib deinen Namen ein:");
                saveToLeaderboard(playerName, score, percentage);
                showLeaderboard();
            }

            resultContainer.style.display = "block";
        }

        function saveToLeaderboard(playerName, playerScore, playerPercentage) {
            let leaderboard = JSON.parse(localStorage.getItem("leaderboard")) || [];

            leaderboard.push({
                name: playerName,
                score: playerScore,
                percentage: playerPercentage
            });

            leaderboard.sort((a, b) => b.percentage - a.percentage);

            leaderboard = leaderboard.slice(0, 15);

            localStorage.setItem("leaderboard", JSON.stringify(leaderboard));
        }

        function showLeaderboard() {
            const leaderboard = JSON.parse(localStorage.getItem("leaderboard")) || [];

            leaderboardContainer.innerHTML = "<h2>Top 15 Rangliste</h2>";
            if (leaderboard.length > 0) {
                leaderboard.forEach((entry, index) => {
                    leaderboardContainer.innerHTML += `<p>${index + 1}. ${entry.name} - ${entry.score} von ${questions.length} (${entry.percentage.toFixed(2)}%)</p>`;
                });
            } else {
                leaderboardContainer.innerHTML += "<p>Die Rangliste ist noch leer.</p>";
            }
        }

        function checkAnswer() {
            const selectedAnswer = this.querySelector('input[name="answer"]');
            if (selectedAnswer) {
                const currentQuestion = questions[currentQuestionIndex];

                const answers = answersContainer.querySelectorAll('label');
                answers.forEach(answer => answer.removeEventListener('click', checkAnswer));

                if (selectedAnswer.value === currentQuestion.KorrekteAntwort) {
                    selectedAnswer.parentElement.style.backgroundColor = "#2ecc71";
                    score++;
                } else {
                    selectedAnswer.parentElement.style.backgroundColor = "#e74c3c";

                    const correctAnswerLabel = Array.from(answers)
                        .find(answer => answer.querySelector('input').value === currentQuestion.KorrekteAntwort);
                    
                    if (correctAnswerLabel) {
                        correctAnswerLabel.style.backgroundColor = "#2ecc71";
                    }
                }

                currentQuestionIndex++;

                saveQuizState();

                setTimeout(() => {
                    if (currentQuestionIndex < questions.length) {
                        showQuestion();
                    } else {
                        showResult();
                    }
                }, 1000);
            }
        }

        resetButton.addEventListener("click", function () {
            currentQuestionIndex = 0;
            score = 0;
            saveQuizState();
            showQuestion();
            resultContainer.style.display = "none";
            leaderboardContainer.innerHTML = "";
        });

        loadQuestions();
    });

</script>
</body>

</html>
