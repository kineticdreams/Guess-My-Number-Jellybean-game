<?php session_start(); ?>
<?php
function testInput($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css?family=Fugaz+One" rel="stylesheet">
    <link rel="stylesheet" href="css/master.css">
    <title>Jellybean Game</title>

</head>
<body>

<header>
    <h1>Jellybean Game</h1>
</header>

<main>
    <form class="userGuess" action="<?php echo $_SERVER['php_self'] ?>" method="post" autocomplete="off">

    <?php
    // <------------- INITIALIZATION CODE BLOCK
    if (!isset($_SESSION["number"]) || !isset($_SESSION["maxRange"])) { // check if $_SESSION["number"] variable is set
    ?>
        <input type="text" name="maxRange" class="noborder" id="range" maxlength="<?php echo strlen((string)PHP_INT_MAX); ?>" placeholder="Input a range number">
        <button type="submit" name="guess" id="rangeButton" class="noborder">Let's GO!</button>

        <?php
        $input = testInput($_POST["selectNumber"]); // sanitize the input
        $maxRange = testInput($_POST["maxRange"]);
        if (empty($maxRange)){
            $errors[] = "Please input a range number";
        } else {
            if (!is_numeric($maxRange)) {
                $errors[] = "$maxRange is not a number!";
            } else {
                if ($maxRange < 1) {
                    $errors[] = "Your number has to be > 0";
                }
                if ($maxRange > PHP_INT_MAX) {
                    $errors[] = "Your range number has to be =< " . PHP_INT_MAX; //maximum value for integer number in PHP
                }
            }
        } // <------ end of input error checking

        if ($errors) {//if there are errors display them
            foreach (array_unique($errors) as $error) {
                echo "<p class='error noborder'>$error</p>";
            }
        } else {
            $_SESSION["maxRange"] = $_POST["maxRange"];
            $randomNumber = rand(1, $_SESSION["maxRange"]);// if is not set then initialize $random variable
            $_SESSION["number"] = $randomNumber;// this variable hold the random number
            $_SESSION["guesses"] = 0;// this one will hold the number of player's guesses
        }
    }
        // <------------- END OF INITIALIZATION CODE BLOCK


        if (isset($_SESSION["number"])) {// if $_SESSION["number"] exists then display the form
            ?>
            <input type="text" name="selectNumber" id="selectNumber" class="noborder" maxlength="<?php echo strlen((string)PHP_INT_MAX); ?>" placeholder="Input a number">
            <button type="submit" name="guess" id="guess" class="noborder">Guess the number!</button>
            <script>
                document.querySelector("#rangeButton").classList.add("hidden");
                document.querySelector("#range").classList.add("hidden");
            </script>
        <?php
        $input = testInput($_POST["selectNumber"]); //trim and sanitize the input
        $maxRange = testInput($_SESSION["maxRange"]);

        if (empty($input)) {// <------ start checking for input errors
            $errors[] = "Please input a number";
        } else {
            if (!is_numeric($input)) {
                $errors[] = "$input is not a number!";
            } else {
                if ($input < 1) {
                    $errors[] = "Your number has to be > 0";
                }
                if ($input > $maxRange) {
                    $errors[] = "Your number has to be =< $maxRange";
                }
            }
        } // <------ end of input error checking

        if ($errors) {//if there are errors display them
            foreach (array_unique($errors) as $error) {
                echo "<p class='error noborder'>$error</p>";
            }
        } else {
            $_SESSION["guesses"]++;//if no errors increment the guesses variable
            if ($input > $_SESSION["number"]) {
                echo "<p class='noborder message high'>$input is too high!</p>";
            } elseif ($input < $_SESSION["number"]) {
                echo "<p class='noborder message low'>$input is too low!</p>";
            } else {
                $plural = $_SESSION['guesses'] === 1 ? "attempt" : "attempts";
                echo "<p class='noborder message win big'>Congratulations!</p>";
                echo "<p class='noborder message win'>It took you {$_SESSION['guesses']} $plural to guess my number!</p>";

                session_destroy();
                ?>
                <script>//
                    document.querySelector("#selectNumber").classList.add("hidden");
//                    document.querySelector("#selectNumber").classList.add("hidden");
                    const playAgain = document.querySelector("#guess");
                    const mainEl = document.querySelector("main");
                    mainEl.classList.add("pulse");//adds an animation effect to <main> tag
                    // playAgain.classList.remove("hidden");
                    playAgain.style.top = "280px";
                    playAgain.innerHTML = "<a href='index.php'>Play Again</a>";//restarts the game
                    // document.querySelector("#range").classList.remove("hidden");
                </script>
                <?php
            }
        }
    } // end of check for $_SESSION["number"] block
        ?>
    </form>
</main>

<!--<script>-->
<!--    const INPUT = document.querySelector("input");-->
<!--    INPUT.addEventListener("keyup", function (event) {-->
<!--        event.preventDefault();-->
<!--        INPUT.style.width = ((this.value.length + 1) * 10) + 'px';-->
<!--    });-->
<!--</script>-->


<?php
echo "Guess number: {$_SESSION["number"]} <br>";
echo "Number of guesses: {$_SESSION["guesses"]}<br>";
echo "Max range number: {$_SESSION["maxRange"]}<br>";
?>
</body>
</html>
