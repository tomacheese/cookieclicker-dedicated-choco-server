const isRunningElement = document.getElementById("isRunning")
const metricsElement = document.getElementById("metrics")
const screenshotElement = document.getElementById("screenshot")
const datetimeElement = document.getElementById("datetime")

const metricsJapaneses = {
    "CC.cookies": "現在の所持クッキー枚数",
    "CC.cookiesPerSecond": "一秒あたりのクッキー生産量",
    "CC.cookiesEarned": "現世で生産したクッキー枚数",
    "CC.cookiesAllTime": "前世含む生産したクッキー枚数",
}

function formatDate(date, format) {
    format = format.replace(/yyyy/g, String(date.getFullYear()))
    format = format.replace(/MM/g, ('0' + (date.getMonth() + 1)).slice(-2))
    format = format.replace(/dd/g, ('0' + date.getDate()).slice(-2))
    format = format.replace(/HH/g, ('0' + date.getHours()).slice(-2))
    format = format.replace(/mm/g, ('0' + date.getMinutes()).slice(-2))
    format = format.replace(/ss/g, ('0' + date.getSeconds()).slice(-2))
    format = format.replace(/SSS/g, ('00' + date.getMilliseconds()).slice(-3))
    return format
}

function load() {
    axios
        .get("api.php?action=status")
        .then(function (response) {
            if (!response.data.status) {
                alert("Error: " + response.data.message);
                return
            }
            const running = response.data.running;
            const metrics = response.data.metrics;
            if (running) {
                isRunningElement.innerHTML = "Running";
                isRunningElement.style.color = "green";
            } else {
                isRunningElement.innerHTML = "Stopped";
                isRunningElement.style.color = "red";
            }
            metricsElement.innerHTML = ""
            if (metrics) {
                for (const metric of metrics) {
                    const li = document.createElement("li")
                    li.innerHTML = metricsJapaneses[metric.name] + ": " + metric.value
                    metricsElement.appendChild(li)
                }
            }

            screenshotElement.src = "screenshot.png?" + new Date().getTime()

            datetimeElement.innerHTML = formatDate(new Date(), "yyyy/MM/dd HH:mm:ss")
        })
        .catch(function (err) {
            console.log(err)
            isRunningElement.innerHTML = "Error: " + err.message
        })
}

function start() {
    axios
        .get("api.php?action=start")
        .then(function (response) {
            if (!response.data.status) {
                alert("Error: " + response.data.message);
                return
            }
            alert(response.data.message)
        })
        .catch(function (err) {
            alert("Start Error: " + err.message)
        })
}

function stop() {
    axios
        .get("api.php?action=stop")
        .then(function (response) {
            if (!response.data.status) {
                alert("Error: " + response.data.message);
                return
            }
            alert(response.data.message)
        })
        .catch(function (err) {
            alert("Stop Error: " + err.message)
        })
}

function pcRestart() {
    axios
        .get("api.php?action=pc-restart")
        .then(function (response) {
            if (!response.data.status) {
                alert("Error: " + response.data.message);
                return
            }
            alert(response.data.message)
        })
        .catch(function (err) {
            alert("PC restart Error: " + err.message)
        })
}

setInterval("load()", 10000);
load();