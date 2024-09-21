// Initialize IndexedDB (existing code)
let db;
const request = indexedDB.open("ExamResultsDB", 1);

// Database setup code remains unchanged...
request.onupgradeneeded = function(e) {
    let db = e.target.result;
    let objectStore = db.createObjectStore("results", { keyPath: "studentId" });
    objectStore.createIndex("subject", "subject", { unique: false });
    objectStore.createIndex("marks", "marks", { unique: false });
    console.log("Database setup complete");
};

// Add result functionality remains unchanged...

// Dark Mode Toggle
document.getElementById('darkModeToggle').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
});

// Check result functionality remains unchanged...
