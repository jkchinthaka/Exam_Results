
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Results System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Insert the CSS code from style.css here */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fc;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            transition: background-color 0.5s ease;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: #1e1e2d;
            color: #e0e0e0;
        }

        /* Container */
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            transition: background-color 0.5s ease;
        }

        /* Dark Mode for Container */
        body.dark-mode .container {
            background-color: #2c2c3e;
        }

        /* Headings */
        h1, h2 {
            text-align: center;
            font-size: 1.8em;
            color: #333;
        }

        /* Form Labels */
        label {
            margin-top: 10px;
            font-size: 1rem;
        }

        /* Input Fields */
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        /* Dark Mode Input Fields */
        body.dark-mode input {
            background-color: #3a3a4f;
            color: #e0e0e0;
        }

        /* Buttons */
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Hover Effect */
        button:hover {
            background-color: #0056b3;
        }

        /* Dark Mode for Button */
        body.dark-mode button {
            background-color: #4654ea;
        }

        /* Result Display */
        #resultDisplay {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f5;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        body.dark-mode #resultDisplay {
            background-color: #33334d;
        }

        /* Toggle Dark Mode Button */
        .toggle-dark-mode {
            margin-top: 20px;
            background-color: #333;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .toggle-dark-mode:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Examination Results</h1>

        <!-- Form for Adding Results -->
        <h2>Add Results</h2>
        <form id="addResultForm">
            <label for="studentId">Student ID:</label>
            <input type="text" id="studentId" required>
            <label for="subject">Subject:</label>
            <input type="text" id="subject" required>
            <label for="marks">Marks:</label>
            <input type="number" id="marks" required>
            <button type="submit">Add Result</button>
        </form>

        <!-- Form for Checking Results -->
        <h2>Check Results</h2>
        <form id="checkResultForm">
            <label for="checkStudentId">Student ID:</label>
            <input type="text" id="checkStudentId" required>
            <button type="submit">Check Result</button>
        </form>

        <!-- Dark Mode Toggle -->
        <button class="toggle-dark-mode" id="darkModeToggle">Toggle Dark Mode</button>

        <!-- Display Result -->
        <div id="resultDisplay"></div>
    </div>

    <script>
        // JavaScript code (insert from script.js here)
        
        // Initialize IndexedDB
        let db;
        const request = indexedDB.open("ExamResultsDB", 1);

        request.onerror = function() {
            console.log("Database failed to open");
        };

        request.onsuccess = function() {
            console.log("Database opened successfully");
            db = request.result;
        };

        request.onupgradeneeded = function(e) {
            let db = e.target.result;
            let objectStore = db.createObjectStore("results", { keyPath: "studentId" });
            objectStore.createIndex("subject", "subject", { unique: false });
            objectStore.createIndex("marks", "marks", { unique: false });
            console.log("Database setup complete");
        };

        // Add result
        document.getElementById('addResultForm').addEventListener('submit', addResult);

        function addResult(e) {
            e.preventDefault();

            let studentId = document.getElementById('studentId').value;
            let subject = document.getElementById('subject').value;
            let marks = document.getElementById('marks').value;

            let newItem = { studentId, subject, marks };

            let transaction = db.transaction(["results"], "readwrite");
            let objectStore = transaction.objectStore("results");
            let request = objectStore.add(newItem);

            request.onsuccess = function() {
                document.getElementById('studentId').value = '';
                document.getElementById('subject').value = '';
                document.getElementById('marks').value = '';
                alert("Result added successfully");
            };

            transaction.oncomplete = function() {
                console.log("Transaction completed");
            };

            transaction.onerror = function() {
                console.log("Transaction failed");
            };
        }

        // Check result
        document.getElementById('checkResultForm').addEventListener('submit', checkResult);

        function checkResult(e) {
            e.preventDefault();
            
            let checkStudentId = document.getElementById('checkStudentId').value;
            let transaction = db.transaction(["results"], "readonly");
            let objectStore = transaction.objectStore("results");
            let request = objectStore.get(checkStudentId);

            request.onsuccess = function() {
                if (request.result) {
                    document.getElementById('resultDisplay').innerHTML = `
                        <p>Student ID: ${request.result.studentId}</p>
                        <p>Subject: ${request.result.subject}</p>
                        <p>Marks: ${request.result.marks}</p>
                    `;
                } else {
                    document.getElementById('resultDisplay').innerHTML = `
                        <p>No result found for Student ID ${checkStudentId}</p>
                    `;
                }
            };
        }

        // Dark Mode Toggle
        document.getElementById('darkModeToggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</body>
</html>
