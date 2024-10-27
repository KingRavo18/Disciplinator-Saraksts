<?php

use PHPUnit\Framework\TestCase;

class FileUploadTest extends TestCase
{
    protected $dbConnection;

    protected function setUp(): void
    {
        // Mock database connection, e.g., with an SQLite in-memory database.
        $this->dbConnection = new mysqli('localhost', 'root', '', 'Disciplinators'); 
    }

    protected function tearDown(): void
    {
        $this->dbConnection->close();
    }

    public function testFileUploadOnlyAllowsPdf()
    {
        $_FILES = [
            'file' => [
                'name' => 'sample.txt',
                'type' => 'text/plain',
                'tmp_name' => __DIR__ . '/sample.txt',
                'error' => UPLOAD_ERR_OK,
                'size' => 123
            ]
        ];

        $_SESSION['user_id'] = 1;
        $_POST['book_id'] = 1;

        ob_start();
        include '../public/ListForBooks/upload_bookfile.php'; 
        $output = ob_get_clean();

        $this->assertStringContainsString('Only PDF files are allowed.', $output);
    }

    public function testSuccessfulPdfUpload()
    {
        $_FILES = [
            'file' => [
                'name' => 'sample.pdf',
                'type' => 'application/pdf',
                'tmp_name' => __DIR__ . '/sample.pdf', // Assume this file exists and is a valid PDF
                'error' => UPLOAD_ERR_OK,
                'size' => 123
            ]
        ];

        $_SESSION['user_id'] = 1;
        $_POST['book_id'] = 1;

        $uploadDir = '../public/ImageUploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); 
        }
        $expectedFilePath = $uploadDir . 'sample.pdf';

        if (file_exists($expectedFilePath)) {
            unlink($expectedFilePath); 
        }

        ob_start();
        include '../public/ListForBooks/upload_bookfile.php';
        ob_get_clean();

        $this->assertFileExists($expectedFilePath);
        // Optionally check the database for the record
        $query = $this->dbConnection->prepare("SELECT file_path FROM bookfile WHERE book_id = ?");
        $query->bind_param("i", $_POST['book_id']);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $this->assertEquals($expectedFilePath, $row['file_path']);
    }
}
