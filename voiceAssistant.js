// Check if browser supports Web Speech API
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
const SpeechSynthesis = window.speechSynthesis;

if (SpeechRecognition) {
    const recognition = new SpeechRecognition();
    const synth = window.speechSynthesis;

    recognition.lang = 'en-US';  // Set language
    recognition.continuous = false;  // Stop after one command
    recognition.interimResults = false;  // Show final results only

    const voiceBtn = document.getElementById('voice-btn');
    const output = document.getElementById('output');

    // Function to speak back to the user
    function speak(text) {
        const utterance = new SpeechSynthesisUtterance(text);
        synth.speak(utterance);
    }

    // Function to handle the recognized speech
    function handleCommand(command) {
        command = command.toLowerCase();

        if (command.includes('open home page')) {
            speak('Opening the home page');
            window.location.href = 'index.html';  // Redirect to contact page
        } else if (command.includes('open login page')) {
            speak('Opening the login page');
            window.location.href = 'login.php';  // Redirect to login page
        } else if (command.includes('open about page')) {
            speak('Opening the about page');
            window.location.href = 'about-us.html';  // Redirect to home page
        } else if (command.includes('open shopping cart page')) {
            speak('Opening the shopping cart page');
            window.location.href = 'bedcart.html';  // Redirect to home page
        }
        else {
            speak('Sorry, I did not understand that command.');
        }
    }

    // Activate voice assistant when the button is clicked
    voiceBtn.addEventListener('click', () => {
        recognition.start();  // Start listening for voice commands
    });

    // Capture speech result
    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        output.textContent = `You said: ${transcript};`  // Display recognized text
        handleCommand(transcript);  // Pass recognized text for command handling
    };

    // Handle errors or when recognition stops
    recognition.onerror = (event) => {
        speak('Sorry, I could not recognize your speech. Please try again.');
    };

    recognition.onend = () => {
        recognition.stop();  // Stop recognition after command is processed
    };
} else {
    alert('Sorry, your browser does not support the Web Speech API.');
}