<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Змінні з форми
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    // Початкове значення для повідомлення про файл
    $fileMessage = "Файл не додано.";

    // Перевірка наявності файлу
    $fileAttached = isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK;

    // Якщо файл завантажено
    if ($fileAttached) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileContent = chunk_split(base64_encode(file_get_contents($fileTmpPath)));
        $fileMessage = "Файл \"$fileName\" успішно додано.";
    } else {
        // Обробка помилок
        if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $fileMessage = "Файл занадто великий.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $fileMessage = "Файл було завантажено лише частково.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $fileMessage = "Відсутній тимчасовий каталог.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $fileMessage = "Не вдалося записати файл на диск.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $fileMessage = "Завантаження файлу зупинено через розширення.";
                    break;
                default:
                    $fileMessage = "Сталася невідома помилка.";
            }
        }
    }

    // Параметри листа
    $to = "info@whitepeak.kiev.ua"; // Ваша пошта
    $subject = "Новий запит від користувача: $name";

    // Формуємо тіло листа
    $body = "
        <html>
        <head>
            <title>Новий запит від користувача</title>
        </head>
        <body>
            <h2>Деталі запиту</h2>
            <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
                <tr>
                    <th style='text-align: left; background-color: #f2f2f2;'>Поле</th>
                    <th style='text-align: left; background-color: #f2f2f2;'>Значення</th>
                </tr>
                <tr>
                    <td>Ім'я</td>
                    <td>$name</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>$email</td>
                </tr>
                <tr>
                    <td>Телефон</td>
                    <td>$phone</td>
                </tr>
                <tr>
                    <td>Повідомлення</td>
                    <td>$message</td>
                </tr>
                <tr>
                    <td>Файл</td>
                    <td>$fileMessage</td>
                </tr>
            </table>
        </body>
        </html>
    ";

    // Заголовки для HTML-листа
    $boundary = md5(time());
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $headers .= "From: $email\r\n";

    // Формуємо тіло листа з прикріпленим файлом
    $emailBody = "--$boundary\r\n";
    $emailBody .= "Content-Type: text/html; charset=utf-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= $body . "\r\n";

    if ($fileAttached) {
        $emailBody .= "--$boundary\r\n";
        $emailBody .= "Content-Type: $fileType; name=\"$fileName\"\r\n";
        $emailBody .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
        $emailBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $emailBody .= $fileContent . "\r\n";
    }

    $emailBody .= "--$boundary--";

    // Надсилання листа
    if (mail($to, $subject, $emailBody, $headers)) {
        echo "
        <div class='success-message'>
            <h1>Ваш запит надіслано успішно!</h1>
            <p>Дякуємо, $name, ми зв'яжемося з вами найближчим часом.</p>
            <p><strong style='color: gray;'>$fileMessage</strong></p>
        </div>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f9f9f9;
                font-family: Arial, sans-serif;
            }
            .success-message {
                text-align: center;
                background: #fff;
                border: 2px solid #4CAF50;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            .success-message h1 {
                color: #4CAF50;
                font-size: 24px;
                margin-bottom: 10px;
            }
            .success-message p {
                font-size: 18px;
                color: #333;
            }
            .success-message p strong {
                color: gray; /* Сірий колір для тексту $fileMessage */
            }
        </style>
        ";
    } else {
        echo "Вибачте, сталася помилка. Спробуйте пізніше.";
    }
    
}
?>
