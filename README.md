**THE EXAM GRIMOIRE**

Heed these ancient texts. Thy survival depends upon it.

### I. HTML & FORMS

- **Form Essentials:** `<form action="target.php" method="POST" enctype="multipart/form-data">`. `enctype` is strictly required for file uploads.
- **Inputs:** `<input type="text|password|radio|checkbox|file|hidden|submit">`.
- **Selects:** `<select name="item"><option value="1">One</option></select>`. Use `name="items[]"` with `multiple="multiple"` for arrays.
- **Tables:** `<table>`, `<thead>`, `<tbody>`, `<tr>` (row), `<th>` (header cell), `<td>` (data cell). Merge using `colspan` and `rowspan`.
- **Semantics:** `<header>`, `<nav>`, `<main>`, `<footer>`.

### II. BOOTSTRAP 5

- **Flexbox:** `.d-flex`. Control alignment with `.justify-content-start|end|center|between` and `.align-items-start|end|center`.
- **Forms:** Wrap inputs in `.form-control`. Labels require `.form-label`. For switches, use `.form-check-wrapper` and `.form-switch`.
- **Input Groups:** Append/prepend icons or text using `.input-group` and `.input-group-text`.
- **Buttons:** `.btn`, `.btn-primary`, `.btn-danger`. Block buttons use parent `.d-grid`.
- **Tables:** `.table`, `.table-striped`, `.table-bordered`, `.table-hover`, `.table-dark`. Wrap in `.table-responsive` for scrolling.
- **Modals:** Requires `<div class="modal fade" id="myModal">`. Trigger via button: `data-bs-toggle="modal" data-bs-target="#myModal"`.

### III. PHP & SUPERGLOBALS (THY GITHUB CUE)

- **`$_GET` vs `$_POST`:** `$_GET` appends to URL (insecure). `$_POST` hides in HTTP body. Fetch via `$_POST['fieldName']`.
- **`$_REQUEST`:** Catches both GET and POST. Sloppy, but functional.
- **`$_SERVER`:** Vital for routing. Check method via `$_SERVER['REQUEST_METHOD'] == 'POST'`.
- **`$_FILES`:** 2D array for uploads. `$_FILES['inputName']['name']` (filename), `['tmp_name']` (temp path). Use `move_uploaded_file()`.
- **Sessions (`$_SESSION`):** Must call `session_start()` at the very top before any HTML. Store data: `$_SESSION['user'] = 'John'`. Destroy: `session_destroy()`.
- **Cookies (`$_COOKIE`):** `setcookie("name", "value", time() + 3600);`.
- **File Inclusion:** `include("file.php")` (warns if missing), `require("file.php")` (kills script if missing).
- **File I/O:** `file_get_contents("file.txt")`, `file_put_contents("file.txt", $data, FILE_APPEND)`.
- **Validation (Regex):** `preg_match("/[a-zA-Z0-9]+/", $string)`. `^` (start), `$` (end), `*` (0 or more), `+` (1 or more).

### IV. PHP OBJECT-ORIENTED PROGRAMMING

- **Classes:** `class Blueprint { public $var; }`.
- **Constructor:** `public function __construct($val) { $this->var = $val; }`.
- **Instantiation:** `$obj = new Blueprint('data');`.
- **Inheritance:** `class Child extends Parent { ... }`.

### V. DATABASE (CRUD ESSENTIALS)

- **Connect (MySQLi):** `$conn = new mysqli($host, $user, $pass, $db);`. Check `$conn->connect_error`.
- **Create (Insert):** `$conn->query("INSERT INTO table (col) VALUES ('val')");`.
- **Read (Select):** `$result = $conn->query("SELECT * FROM table"); while($row = $result->fetch_assoc()) { echo $row['col']; }`.
- **Update:** `$conn->query("UPDATE table SET col='val' WHERE id=1");`.
- **Delete:** `$conn->query("DELETE FROM table WHERE id=1");`.
- _Sanitization:_ Always use `$conn->real_escape_string($_POST['data'])` to ward off SQL injection.
