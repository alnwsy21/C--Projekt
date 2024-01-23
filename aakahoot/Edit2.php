<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kahoot-Style Quiz Admin</title>
    <link rel="stylesheet" href="edit.css">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>

<body>
    <button id="logoutBtn">Logout</button>
    <h1 class="admin">Admin Bereich</h1>

    <div id="particles-js"></div>

    <div id="quiz-container">
        <div id="quiz">
            <div id="question-container">
                <h2 id="question">Frage hier...</h2>
                <p id="question-counter">Frage: <span id="current-question-number">0</span> von <span
                        id="total-questions-number">0</span></p>
            </div>
            <div id="answers-container">
                <!-- Antworten werden hier eingefügt -->
            </div>
            <button id="next-btn">Nächste Frage</button>
            <button id="last-btn">Letzte Frage</button>
            <button id="reset-btn">Quiz zurücksetzen</button>
            <button id="add-question-btn">Frage hinzufügen</button>
            <button id="edit-question-btn">Frage bearbeiten</button>
            <button id="delete-question-btn">Frage löschen</button>
            <button id="show-question-btn">Frage anzeigen</button>
        </div>
        <div id="result">
            <!-- Ergebnisse werden hier angezeigt -->
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const questionContainer = document.getElementById("question");
            const questionCounter = document.getElementById("question-counter");
            const answersContainer = document.getElementById("answers-container");
            const nextButton = document.getElementById("next-btn");
            const lastButton = document.getElementById("last-btn");
            const resultContainer = document.getElementById("result");
            const resetButton = document.getElementById("reset-btn");
            const addButton = document.getElementById("add-question-btn");
            const editButton = document.getElementById("edit-question-btn");
            const deleteButton = document.getElementById("delete-question-btn");
            const logoutButton = document.getElementById("logoutBtn");
            const showQuestionButton = document.getElementById("show-question-btn");

            let currentQuestionIndex = 0;
            let questions;
            let score = 0; // Neues Score-Element hinzugefügt

            function loadQuestions() {
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        questions = JSON.parse(xhr.responseText);
                        showQuestion();
                    }
                };
                xhr.open("GET", "get_questions.php", true);
                xhr.send();
            }

            function showQuestion() {
                const currentQuestion = questions[currentQuestionIndex];
                questionContainer.textContent = currentQuestion.Frage;
                updateQuestionCounter();

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
                    nextButton.classList.remove("visible");
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
                        score++; // Inkrementiere den Score, wenn die Antwort richtig ist
                    } else {
                        selectedAnswer.parentElement.style.backgroundColor = "#e74c3c";

                        const correctAnswerLabel = Array.from(answers)
                            .find(answer => answer.querySelector('input').value === currentQuestion.KorrekteAntwort);

                        if (correctAnswerLabel) {
                            correctAnswerLabel.style.backgroundColor = "#2ecc71";
                        }
                    }

                    currentQuestionIndex++;

                    setTimeout(() => {
                        if (currentQuestionIndex < questions.length) {
                            showQuestion();
                        } else {
                            showResult();
                        }
                    }, 1000);
                }
            }

            function updateQuestionCounter() {
                const currentQuestionNumberElement = document.getElementById("current-question-number");
                const totalQuestionsNumberElement = document.getElementById("total-questions-number");

                currentQuestionNumberElement.textContent = currentQuestionIndex;
                totalQuestionsNumberElement.textContent = questions.length - 1;
            }

            function showResult() {
                const percentage = (score / 30) * 100; // Gesamtfragenanzahl ist 30
                resultContainer.innerHTML = `<h2>Quiz beendet! Dein Ergebnis: ${score} von 30 (${percentage.toFixed(2)}%)</h2>`;

                resultContainer.style.display = "block";
            }

            nextButton.addEventListener("click", function () {
                if (currentQuestionIndex < questions.length) {
                    showQuestion();
                } else {
                    showResult();
                }
            });

            lastButton.addEventListener("click", function () {
                currentQuestionIndex = questions.length - 1;
                showQuestion();
                resultContainer.style.display = "none";
            });

            resetButton.addEventListener("click", function () {
                currentQuestionIndex = 0;
                score = 0;
                showQuestion();
                resultContainer.style.display = "none";
            });

            addButton.addEventListener("click", addQuestion);
            editButton.addEventListener("click", editQuestion);
            deleteButton.addEventListener("click", deleteQuestion);

            function addQuestion() {
                const newQuestion = {
                    Frage: prompt("Gib die neue Frage ein:"),
                    Antwort1: prompt("Gib die erste Antwort ein:"),
                    Antwort2: prompt("Gib die zweite Antwort ein:"),
                    Antwort3: prompt("Gib die dritte Antwort ein:"),
                    Antwort4: prompt("Gib die vierte Antwort ein:"),
                    KorrekteAntwort: prompt("Gib die korrekte Antwort ein (1-4):")
                };

                questions.push(newQuestion);

                showQuestion();
            }

            function editQuestion() {
                const indexToEdit = prompt("Gib den Index der Frage zum Bearbeiten ein:");

                if (indexToEdit >= 0 && indexToEdit < questions.length) {
                    const editedQuestion = {
                        Frage: prompt("Gib die bearbeitete Frage ein:"),
                        Antwort1: prompt("Gib die bearbeitete erste Antwort ein:"),
                        Antwort2: prompt("Gib die bearbeitete zweite Antwort ein:"),
                        Antwort3: prompt("Gib die bearbeitete dritte Antwort ein:"),
                        Antwort4: prompt("Gib die bearbeitete vierte Antwort ein:"),
                        KorrekteAntwort: prompt("Gib die bearbeitete korrekte Antwort ein (1-4):")
                    };

                    questions[indexToEdit] = editedQuestion;

                    showQuestion();
                } else {
                    alert("Ungültiger Index");
                }
            }

            function deleteQuestion() {
                const indexToDelete = prompt("Gib den Index der Frage zum Löschen ein:");

                if (indexToDelete >= 0 && indexToDelete < questions.length) {
                    questions.splice(indexToDelete, 1);

                    if (currentQuestionIndex < questions.length) {
                        showQuestion();
                    } else {
                        showResult();
                    }
                } else {
                    alert("Ungültiger Index");
                }
            }

            logoutButton.addEventListener("click", function () {
                window.location.href = "login.php";
            });

            showQuestionButton.addEventListener("click", function () {
                const indexToShow = prompt("Gib den Index der Frage ein, die du anzeigen möchtest:");

                if (indexToShow >= 0 && indexToShow < questions.length) {
                    currentQuestionIndex = indexToShow;
                    showQuestion();
                    resultContainer.style.display = "none";
                } else {
                    alert("Ungültiger Index");
                }
            });

            loadQuestions();
        });
    </script>
</body>

</html>
