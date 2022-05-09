const okGoogles = [
    "OK Google",
    "Ok Google",
    "Hey Google",
    "おっけーぐーぐる",
    "オッケーgoogle",
    "オッケー、google"
];

let recognition = null;
let okGoogleDate = null;

function start_recognition(recognitionId) {
    document.getElementById("status-message").innerHTML = "Listening...";
    SpeechRecognition = webkitSpeechRecognition || SpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.continuous = false;
    recognition.interimResults = true;
    recognition.lang = "ja";
    recognition.maxAlternatives = 1;
    recognition.onresult = function (event) {
        console.log(event);
        if (event.results.length > 0) {
            document.getElementById("result").innerHTML = event.results[event.resultIndex][0].transcript;
            document.getElementById("result").style.color = event.results[event.resultIndex].isFinal ? "green" : "lime"

            if (checkOkGoogle(event.results[event.resultIndex][0].transcript)) {
                if (okGoogleDate !== null && okGoogleDate.getTime() > new Date().getTime() - 1000 * 10) {
                    return // 10秒以内だったら反応しない
                }
                okGoogleDate = new Date();
                document.getElementById("okGoogle").innerHTML = "OK GOOGLE";
                axios.get("api.php", {
                    params: {
                        action: "okGoogle"
                    }
                }).then(function (response) {
                    console.log(response);
                }).catch(function (error) {
                    console.log(error);
                })
            } else {
                document.getElementById("okGoogle").innerHTML = "";
            }
        }
    };
    recognition.onend = function (event) {
        console.log("onend", event);
        document.getElementById("status-circle").style.backgroundColor = "red";
        start_recognition(recognitionId + 1);
    };
    recognition.onstart = function (event) {
        console.log("onstart", event);
        document.getElementById("status-circle").style.backgroundColor = "cyan";
    };
    recognition.onaudiostart = function (event) {
        console.log("onaudiostart", event);
        document.getElementById("status-circle").style.backgroundColor = "limegreen";
    };
    recognition.onaudioend = function (event) {
        console.log("onaudioend", event);
        document.getElementById("status-circle").style.backgroundColor = "cyan";
    };
    recognition.onspeechstart = function (event) {
        console.log("onspeechstart", event);
        document.getElementById("status-circle").style.backgroundColor = "lime";
    };
    recognition.onspeechend = function (event) {
        console.log("onspeechend", event);
        document.getElementById("status-circle").style.backgroundColor = "limegreen";
    };
    recognition.onnomatch = function (event) {
        console.log("onnomatch", event);
    }
    recognition.onerror = function (event) {
        console.log("onerror", event);
        document.getElementById("status-circle").style.backgroundColor = "red";
        document.getElementById("status-message").innerHTML = event.error;
    };
    recognition.start();
}

function checkOkGoogle(transcript) {
    for (const okGoogle of okGoogles) {
        if (transcript.toLowerCase().includes(okGoogle.toLowerCase())) {
            return true;
        }
    }
    return false;
}
start_recognition(0)