<?php
/**
 * LFI (Local File Inclusion) PoC - Educational Purpose Only
 * 
 * VULNERABILITY EXPLANATION:
 * This script intentionally does NOT implement:
 * - Path traversal validation
 * - Whitelist checks
 * - Input sanitization
 * - Realpath() validation
 * - Safe file inclusion
 * 
 * DEVELOPER MISTAKES LEADING TO LFI:
 * 1. Trusting user input without validation
 * 2. Not checking for path traversal sequences (../, ..\)
 * 3. Not using basename() to restrict to filename only
 * 4. Not using realpath() to verify file location
 * 5. Not maintaining a whitelist of allowed files
 * 6. Using include/require with user-controlled variables
 */

// Initialize logs array
$logs = array();
$logs[] = "[" . date('Y-m-d H:i:s') . "] Request received";

// Log request method and parameters
$logs[] = "[" . date('Y-m-d H:i:s') . "] Method: " . $_SERVER['REQUEST_METHOD'];
$logs[] = "[" . date('Y-m-d H:i:s') . "] User IP: " . $_SERVER['REMOTE_ADDR'];

$file_content = null;
$error_message = null;
$file_path = null;

// Check if filename parameter is provided
if (isset($_GET['filename'])) {
    $requested_filename = $_GET['filename'];
    $logs[] = "[" . date('Y-m-d H:i:s') . "] User Input (filename): " . htmlspecialchars($requested_filename);
    
    // VULNERABLE: No path traversal validation!
    // This allows attackers to use ../ sequences to read files outside download directory
    $download_directory = __DIR__ . '/download/';
    $file_path = $download_directory . $requested_filename;
    
    $logs[] = "[" . date('Y-m-d H:i:s') . "] Constructed File Path: " . $file_path;
    
    // VULNERABLE: No realpath() check to verify the file is actually in download directory
    // Attacker can use: ?filename=../index.php to read parent directory files
    
    // Check if file exists
    if (file_exists($file_path)) {
        $logs[] = "[" . date('Y-m-d H:i:s') . "] File exists: TRUE";
        $logs[] = "[" . date('Y-m-d H:i:s') . "] File is readable: " . (is_readable($file_path) ? "TRUE" : "FALSE");
        $logs[] = "[" . date('Y-m-d H:i:s') . "] File size: " . filesize($file_path) . " bytes";
        
        // VULNERABLE: Directly reading file without checking if it's in safe directory
        if (is_readable($file_path)) {
            $logs[] = "[" . date('Y-m-d H:i:s') . "] Reading file content...";
            $file_content = file_get_contents($file_path);
            $logs[] = "[" . date('Y-m-d H:i:s') . "] File content read successfully. Size: " . strlen($file_content) . " bytes";
        } else {
            $error_message = "File is not readable!";
            $logs[] = "[" . date('Y-m-d H:i:s') . "] ERROR: File is not readable";
        }
    } else {
        $error_message = "File not found!";
        $logs[] = "[" . date('Y-m-d H:i:s') . "] ERROR: File does not exist";
    }
    
    $logs[] = "[" . date('Y-m-d H:i:s') . "] Request processing completed";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LFI PoC - Local File Inclusion</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #1e1e1e;
            color: #d4d4d4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #ff6b6b;
            border-bottom: 2px solid #ff6b6b;
            padding-bottom: 10px;
        }
        .warning {
            background-color: #3d2424;
            border-left: 4px solid #ff6b6b;
            padding: 15px;
            margin: 20px 0;
            color: #ffcccc;
        }
        .warning strong {
            color: #ff6b6b;
        }
        .section {
            background-color: #252526;
            border: 1px solid #3e3e42;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .section h2 {
            color: #4ec9b0;
            margin-top: 0;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #ce9178;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            background-color: #3c3c3c;
            color: #d4d4d4;
            border: 1px solid #3e3e42;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #007acc;
        }
        button {
            background-color: #007acc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Courier New', monospace;
        }
        button:hover {
            background-color: #005a9e;
        }
        .output {
            background-color: #1e1e1e;
            border: 1px solid #3e3e42;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .error {
            color: #f48771;
            background-color: #3d2424;
        }
        .success {
            color: #89d185;
            background-color: #283c2e;
        }
        .logs {
            background-color: #1e1e1e;
            border: 1px solid #3e3e42;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            max-height: 300px;
            overflow-y: auto;
        }
        .log-entry {
            color: #858585;
            margin: 5px 0;
            font-size: 12px;
        }
        .log-error {
            color: #f48771;
        }
        .log-success {
            color: #89d185;
        }
        .vulnerability-info {
            background-color: #2d3d2d;
            border-left: 4px solid #89d185;
            padding: 15px;
            margin: 15px 0;
            border-radius: 2px;
        }
        .vulnerability-info strong {
            color: #89d185;
        }
        .vulnerability-info p {
            margin: 8px 0;
        }
        .code-example {
            background-color: #1e1e1e;
            border: 1px solid #3e3e42;
            padding: 10px;
            border-radius: 2px;
            margin: 10px 0;
            color: #ce9178;
        }
        .suggestions {
            background-color: #2d3d3d;
            border-left: 4px solid #4ec9b0;
            padding: 15px;
            margin: 15px 0;
            border-radius: 2px;
        }
        .suggestions strong {
            color: #4ec9b0;
        }
        .suggestions li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔓 LFI (Local File Inclusion) PoC - Educational Demonstration</h1>
        
        <div class="warning">
            <strong>⚠️ CRITICAL WARNING:</strong> This page intentionally contains a Local File Inclusion vulnerability 
            for educational purposes ONLY. This demonstrates how attackers can read arbitrary files from the server. 
            <strong>NEVER use this code in production!</strong>
        </div>

        <div class="section">
            <h2>📝 Input Form</h2>
            <form method="GET">
                <label for="filename">Filename (or use path traversal):</label>
                <input type="text" id="filename" name="filename" placeholder="e.g., demo.txt or ../index.php or ../../../../../etc/passwd">
                <button type="submit">Read File</button>
            </form>
            <div class="vulnerability-info">
                <strong>💡 Try These Payloads:</strong>
                <div class="code-example">demo.txt</div>
                <div class="code-example">../index.php</div>
                <div class="code-example">../ref-poc.php</div>
                <div class="code-example">../../../../../../etc/passwd</div>
            </div>
        </div>

        <?php if ($file_content !== null): ?>
        <div class="section">
            <h2>✅ File Content Retrieved</h2>
            <div class="output success">
<?php echo htmlspecialchars($file_content); ?>
            </div>
            <p><strong>File Path:</strong> <?php echo htmlspecialchars($file_path); ?></p>
            <p><strong>File Size:</strong> <?php echo strlen($file_content); ?> bytes</p>
        </div>
        <?php elseif ($error_message !== null): ?>
        <div class="section">
            <h2>❌ Error</h2>
            <div class="output error">
<?php echo htmlspecialchars($error_message); ?>
            </div>
            <?php if ($file_path): ?>
            <p><strong>Attempted Path:</strong> <?php echo htmlspecialchars($file_path); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="section">
            <h2>🎓 LFI Vulnerability Explanation</h2>
            <div class="vulnerability-info">
                <strong>What is LFI (Local File Inclusion)?</strong>
                <p>
                    LFI is a vulnerability that allows an attacker to read arbitrary files from the server's 
                    filesystem. In this case, the application trusts user input to construct file paths without 
                    proper validation.
                </p>
            </div>

            <h3>❌ Developer Mistakes (Why This Vulnerability Exists):</h3>
            <ol>
                <li><strong>No Input Validation:</strong> The script directly uses user input without checking for malicious patterns</li>
                <li><strong>No Path Traversal Protection:</strong> No checks for ../ or ..\ sequences that allow directory traversal</li>
                <li><strong>No Directory Boundary Check:</strong> Using realpath() would verify the file is in the intended directory</li>
                <li><strong>No Whitelist:</strong> No list of allowed files that can be accessed</li>
                <li><strong>No basename() Function:</strong> Should use basename() to strip directory components from filename</li>
                <li><strong>Direct File Operations:</strong> Using file_get_contents() directly with user input</li>
            </ol>

            <h3>🛡️ How to Prevent LFI:</h3>
            <div class="suggestions">
                <strong>Best Practices for Secure File Access:</strong>
                <ul>
                    <li><strong>Use Whitelist:</strong> Only allow access to specific predefined files</li>
                    <li><strong>Use basename():</strong> Strip directory components to prevent traversal
                        <div class="code-example">$safe_file = basename($_GET['filename']);</div>
                    </li>
                    <li><strong>Use realpath():</strong> Verify the resolved path is within allowed directory
                        <div class="code-example">$real_path = realpath($file_path);
$allowed_dir = realpath('./download/');
if (strpos($real_path, $allowed_dir) !== 0) { die('Access Denied'); }</div>
                    </li>
                    <li><strong>Validate Input:</strong> Check against allowed characters (alphanumeric, underscore, hyphen, dot)</li>
                    <li><strong>Avoid Dynamic Includes:</strong> Never use include/require with user input</li>
                    <li><strong>Disable Dangerous Functions:</strong> Restrict access to sensitive files using web server config</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>📊 Request Logs</h2>
            <div class="logs">
                <?php 
                foreach ($logs as $log) {
                    $class = (strpos($log, 'ERROR') !== false) ? 'log-error' : 
                             (strpos($log, 'TRUE') !== false ? 'log-success' : '');
                    echo '<div class="log-entry ' . $class . '">' . htmlspecialchars($log) . '</div>';
                }
                ?>
            </div>
        </div>

        <div class="section">
            <h2>🔍 Debug Information</h2>
            <p><strong>Current Script Path:</strong> <?php echo htmlspecialchars(__FILE__); ?></p>
            <p><strong>Download Directory:</strong> <?php echo htmlspecialchars(__DIR__ . '/download/'); ?></p>
            <p><strong>Server OS:</strong> <?php echo htmlspecialchars(PHP_OS); ?></p>
            <p><strong>PHP Version:</strong> <?php echo htmlspecialchars(phpversion()); ?></p>
        </div>
    </div>
</body>
</html>
