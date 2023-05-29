<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
<title>記緑 - Teta</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="colores.css">
</head>


<body style="height:100%">

<div style="text-align:center;">
    <h1>記緑 - Teta</h1>
</div>

<form action=add.php>

<?php
$canary = rand();
echo "<input type=\"hidden\" id=\"canary\" name=\"canary\" value=\"$canary\">";
?>

<input type="hidden" id="what" name="what" value="teta">
<!--<input type="hidden" id="izq" name="izq" value="0">
<input type="hidden" id="der" name="der" value="0">-->

<div class="addNuevo">
    <a href="index.php" class="cancelar">Cancelar</a>
    <input type="submit" value="Guardar">
</div>

<hr>

<table>
<tr>
<th>Izquierda</th><th>Derecha</th>
</tr>
<tr>
<td><div class="stopwatch" id="stopwatchLeftTeta"></div></td>
<td><div class="stopwatch" id="stopwatchRightTeta"></div></td>
<tr>
<td><input type="text" inputmode="decimal" id="izq" name="izq" class="memoInputText" style="font-size: 4vw" value="0.00" /> min</td>
<td><input type="text" inputmode="decimal" id="der" name="der" class="memoInputText" style="font-size: 4vw" value="0.00" /> min</td>
</tr>
</tr>
</table>

<div class="memoInput">
    <label for="fmemo">Notas:</label>
    <textarea rows=4" id="fmemo" name="fmemo" class="memoInputText"></textarea>
</div>

<hr>

<label for="horaManual">Hora de registro:</label>
<input type="datetime-local" id="horaManual" name="horaManual">

</form>

<script>
var Stopwatch = function(elem, options) {

  var timer = createTimer(),
    startButton = createButton("▶️", start),
    stopButton = createButton("⏹️", stop),
    resetButton = createButton("↩️", reset),
    offset,
    clock,
    interval;

  // default options
  options = options || {};
  options.delay = options.delay || 1;
  options.updateId = options.updateId || null;

  // append elements     
  elem.appendChild(timer);
  elem.appendChild(startButton);
  elem.appendChild(stopButton);
  elem.appendChild(resetButton);

  // initialize
  reset();

  // private functions
  function createTimer() {
    timerElement = document.createElement("span");
    currentState = 0;
    return timerElement;
  }

  function createButton(action, handler) {
    var a = document.createElement("a");
    a.href = "#" + action;
    a.innerHTML = action;
    a.addEventListener("click", function(event) {
      handler();
      event.preventDefault();
    });
    return a;
  }

  function start() {
    if (!interval) {
        offset = Date.now();
        interval = setInterval(update, options.delay);
      
        if (!timer.classList.contains("runningSW"))
        {
            timer.classList.add("runningSW");
        }
        
        if (timer.classList.contains("stoppedSW"))
        {
            timer.classList.remove("stoppedSW");
        }
    }
  }

  function stop() {
    if (interval) {
        clearInterval(interval);
        interval = null;
        
        if (!timer.classList.contains("stoppedSW"))
        {
            timer.classList.add("stoppedSW");
        }
        
        if (timer.classList.contains("runningSW"))
        {
            timer.classList.remove("runningSW");
        }
    }
  }

  function reset() {
    clock = 0;
    render(0);
    if (!timer.classList.contains("stoppedSW"))
    {
        timer.classList.add("stoppedSW");
    }
    
    if (timer.classList.contains("runningSW"))
    {
        timer.classList.remove("runningSW");
    }
  }

  function update() {
    clock += delta();
    render();
  }

  function render() {
    clocksec = clock / 1000;
    timer.innerHTML = '' + Math.floor(clocksec / 60) + ' min ' + Math.floor(clocksec % 60) + ' s';
    if (options.updateId) {
        options.updateId.value = (clocksec / 60).toFixed(2);
    }
  }

  function delta() {
    var now = Date.now(),
      d = now - offset;

    offset = now;
    return d;
  }

  // public API
  this.start = start;
  this.stop = stop;
  this.reset = reset;
};


// basic examples
var left = document.getElementById("stopwatchLeftTeta");
var right = document.getElementById("stopwatchRightTeta");
new Stopwatch(left, {updateId: document.getElementById("izq")});
new Stopwatch(right, {updateId: document.getElementById("der")});

</script>

</body>
</html>


