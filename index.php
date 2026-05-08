<!-- 
    1- i foucs to php dont botstrop
    2- i dont have any idea about bootstrap style, classes, and how to use it.
    3- so i bring the code from AI and i will edit it to my project.
    4- i can do the style with (HTML + CSS + JS).
    5- dont have engough time to ather course.

            thank you for your understanding.
-->
<?php
// session to meomory in brawser
session_start();
// session_destroy();

// cetogery books
$categorys = ["fictoin", "science", "history", "biography", "tecnology", "non-fiction"];
// if book in ssission
if (!isset($_SESSION["myBook"])) {




    $_SESSION["myBook"] = [
        [
            "id" => 1,
            "title" => "King lear",
            "author" => "lear lear",
            "genre" => "history",
            "year" => 1925,
            "pages" => 180
        ],


        [
            "id" => 2,
            "title" => "King moath",
            "author" => "moath atef",
            "genre" => "Fiction",
            "year" => 1900,
            "pages" => 963

        ],



        [
            "id" => 3,
            "title" => "code with me ",
            "author" => "eng mohammed",
            "genre" => "Fiction",
            "year" => 2000,
            "pages" => 147

        ]
    ];
}


// data from ^^ up vars
$books = $_SESSION["myBook"];


// error msg
$msgErr = [];



// clean input data
function test($data)
{
    $data = trim($data); // remove space
    $data = stripslashes($data); // remove backslashes
    $data = htmlspecialchars($data); // convert special characters to save text
    return $data;
}




// pllus id to new book
$id = 0;
foreach ($books as $b) {
    if ($b["id"] > $id) {
        $id = $b["id"];
    }
}



// check if data submited
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    //title 
    $inputTitle = test($_POST["title"] ?? "");
    if (empty($inputTitle)) {
        $msgErr["title"] = "Title is required!!";
    } elseif (strlen($inputTitle) < 3 || strlen($inputTitle) > 120) {
        $msgErr["title"] = "title must be between 3-120 caracters";
    }



    //authore
    $inputAuthor = test($_POST["author"] ?? "");
    $name = explode(" ", trim($inputAuthor));
    if (empty($inputAuthor)) {
        $msgErr["author"] = "Author is required";
    } elseif (count($name) < 2) {
        $msgErr["author"] = "Author must be two names";
    }



    //genre
// genre
    $inputGenre = test($_POST["genre"] ?? "");

    if (empty($inputGenre)) {
        $msgErr["genre"] = "Genre is required";
    } elseif (!in_array($inputGenre, $categorys)) {
        $msgErr["genre"] = "Invalid genre";
    }


    // year
    $inputYear = test($_POST["year"] ?? "");
    $currentYear = date("Y");
    if (empty($inputYear)) {
        $msgErr["year"] = "Year is required";
    } elseif (
        !is_numeric($inputYear) ||
        $inputYear < 1000 ||
        $inputYear > $currentYear
    ) {
        $msgErr["year"] = "Year must be between 1000 and $currentYear";
    }




    //page
    $inputPages = test($_POST["pages"] ?? "");
    if (empty($inputPages)) {
        $msgErr["pages"] = "Pages count is required";
    } elseif (!is_numeric($inputPages) || $inputPages <= 0) {
        $msgErr["pages"] = "Pages must be a positive number";
    }



    // prepar new arraye
    if (empty($msgErr)) {
        $newBook = [
            "id" => $id + 1,
            "title" => $inputTitle,
            "author" => $inputAuthor,
            "genre" => $inputGenre,
            "year" => (int) $inputYear,
            "pages" => (int) $inputPages
        ];


        // add new book
        $_SESSION["myBook"][] = $newBook;
        $_SESSION["flash_msg"] = "add book sucsesfuly";

        // no add flash msg when refresh page
        header("Location: index.php");
        exit;
    }
}





?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Book Library - 120231375</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
        }

        .error-text {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .card {
            border: none;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <h1 class="text-center mb-5 text-secondary">My Digital Bookshelf</h1>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm p-4">
                    <h4 class="mb-4">Add New Entry</h4>

                    <?php if (isset($_SESSION["flash_msg"])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo $_SESSION["flash_msg"];
                            unset($_SESSION["flash_msg"]); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Book Title</label>
                            <input type="text" name="title" class="form-control"
                                value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                            <?php if (isset($msgErr['title'])): ?>
                                <div class="error-text">
                                    <?php echo $msgErr['title']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Author Name</label>
                            <input type="text" name="author" class="form-control"
                                value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">
                            <?php if (isset($msgErr['author'])): ?>
                                <div class="error-text">
                                    <?php echo $msgErr['author']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Genre</label>
                            <select name="genre" class="form-select">
                                <option value="">-- Choose Genre --</option>
                                <?php foreach ($categorys as $cat): ?>
                                    <option value="<?php echo $cat; ?>" <?php echo (($_POST['genre'] ?? '') == $cat) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($msgErr['genre'])): ?>
                                <div class="error-text">
                                    <?php echo $msgErr['genre']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Year</label>
                                <input type="number" name="year" class="form-control"
                                    value="<?php echo htmlspecialchars($_POST['year'] ?? ''); ?>">
                                <?php if (isset($msgErr['year'])): ?>
                                    <div class="error-text">
                                        <?php echo $msgErr['year']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pages</label>
                                <input type="number" name="pages" class="form-control"
                                    value="<?php echo htmlspecialchars($_POST['pages'] ?? ''); ?>">
                                <?php if (isset($msgErr['pages'])): ?>
                                    <div class="error-text">
                                        <?php echo $msgErr['pages']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 mt-3">Register Book</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="table-responsive bg-white shadow-sm p-3 rounded-4">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Year</th>
                                <th>Pages</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td class="fw-bold">#
                                        <?php echo htmlspecialchars($book['id']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($book['title']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($book['author']); ?>
                                    </td>
                                    <td><span class="badge bg-info text-dark">
                                            <?php echo htmlspecialchars($book['genre']); ?>
                                        </span></td>
                                    <td>
                                        <?php echo htmlspecialchars($book['year']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($book['pages']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>