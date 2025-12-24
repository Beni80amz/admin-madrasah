<?php

/**
 * Storage Link Fixer & Diagnostic Tool - v4
 */

function recursiveRemoveDirectory($directory)
{
    if (!file_exists($directory))
        return true;
    foreach (glob($directory . '/*') as $item) {
        if (is_dir($item)) {
            if (!recursiveRemoveDirectory($item))
                return false;
        } else {
            if (!unlink($item))
                return false;
        }
    }
    return rmdir($directory);
}

echo "<style>body{font-family:sans-serif;line-height:1.6;max-width:900px;margin:20px auto;padding:20px;background:#f4f4f4} .container{background:white;padding:30px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1)} h1{margin-top:0} .card{background:#f8f9fa;border:1px solid #ddd;padding:15px;margin-bottom:15px;border-radius:4px} .status-ok{color:green;font-weight:bold} .status-err{color:red;font-weight:bold} .status-warn{color:orange;font-weight:bold} code{background:#eee;padding:2px 5px;border-radius:3px} table{width:100%;border-collapse:collapse} th,td{text-align:left;padding:8px;border-bottom:1px solid #ddd} .btn{display:inline-block;background:#007bff;color:white;padding:8px 15px;text-decoration:none;border-radius:4px;cursor:pointer;border:none;margin-right:5px;} .btn-danger{background:#dc3545} .btn-warning{background:#f57c00}</style>";

echo "<div class='container'>";
echo "<h1>Storage Diagnostic Tool v4</h1>";

$publicFolder = __DIR__;
$targetFolder = dirname(__DIR__) . '/storage/app/public';
$linkFolder = $publicFolder . '/storage';

// --- Action Handler ---
$msg = "";
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_link') {
        if (is_link($linkFolder) && unlink($linkFolder)) {
            $msg = "<p class='status-ok'>Symbolic link deleted.</p>";
        } else {
            $msg = "<p class='status-err'>Failed to delete link.</p>";
        }
    } elseif ($_POST['action'] === 'delete_dir') {
        if (is_dir($linkFolder) && !is_link($linkFolder)) {
            if (recursiveRemoveDirectory($linkFolder)) {
                $msg = "<p class='status-ok'>Directory deleted successfully.</p>";
            } else {
                $msg = "<p class='status-err'>Failed to delete directory. Permissions denial.</p>";
            }
        }
    } elseif ($_POST['action'] === 'rename_dir') {
        $backupName = $linkFolder . '_backup_' . time();
        if (rename($linkFolder, $backupName)) {
            $msg = "<p class='status-ok'>Directory renamed to " . basename($backupName) . "</p>";
        } else {
            $msg = "<p class='status-err'>Failed to rename directory.</p>";
        }
    }
    // Simple refresh to clear post data not strictly needed if we just show state below, but good for UX
    // header("Location: " . $_SERVER['PHP_SELF']); // Can't header after output
}
if ($msg)
    echo $msg;

// --- Diagnostic 1: APP_URL ---
echo "<div class='card'>";
echo "<h3>1. Environment Configuration</h3>";
$envFile = dirname(__DIR__) . '/.env';
$envAppUrl = 'Not found';
if (file_exists($envFile)) {
    $lines = file($envFile);
    foreach ($lines as $line) {
        if (strpos(trim($line), 'APP_URL=') === 0) {
            $envAppUrl = trim(substr(trim($line), 8));
        }
    }
}
$currentDomain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
echo "<p>Status: ";
if (strpos($envAppUrl, $_SERVER['HTTP_HOST']) !== false) {
    echo "<span class='status-ok'>OK</span>";
} else {
    echo "<span class='status-err'>MISMATCH</span>";
}
echo "</p>";
echo "</div>";

// --- Diagnostic 2: Symlink Status ---
echo "<div class='card'>";
echo "<h3>2. Storage Link Status</h3>";

if (is_link($linkFolder)) {
    $target = readlink($linkFolder);
    echo "<p>Link state: <span class='status-ok'>ACTIVE</span> (Points to: $target)</p>";
    if (realpath($linkFolder) === realpath($targetFolder)) {
        echo "<p>Link functionality: <span class='status-ok'>WORKING</span></p>";
    } else {
        echo "<p>Link functionality: <span class='status-err'>BROKEN</span></p>";
        echo "<form method='post'><button name='action' value='delete_link' class='btn btn-warning'>Delete & Retry</button></form>";
    }
} else if (is_dir($linkFolder)) {
    echo "<p class='status-err'>CONFLICT: 'public/storage' is a real directory.</p>";
    echo "<p>Script tried to delete but might have failed due to permissions.</p>";
    echo "<form method='post' onsubmit=\"return confirm('Try deleting again?');\" style='display:inline-block'>";
    echo "<button name='action' value='delete_dir' class='btn btn-danger'>Force Delete Directory</button>";
    echo "</form>";
    echo "<form method='post' style='display:inline-block'>";
    echo "<button name='action' value='rename_dir' class='btn btn-warning'>Try Rename Instead</button>";
    echo "</form>";
    echo "<p><strong>Manual Fix:</strong> Log into your Hosting cPanel > File Manager. Go to <code>public_html</code> (or public) and manually DELETE the <code>storage</code> folder there.</p>";
} else {
    echo "<p class='status-warn'>Link missing. Attempting creation...</p>";
    if (symlink($targetFolder, $linkFolder)) {
        echo "<p class='status-ok'>Created successfully! Please refresh.</p>";
        echo "<script>window.location.reload();</script>";
    } else {
        echo "<p class='status-err'>Creation failed. Check permissions.</p>";
    }
}
echo "</div>";

// --- Diagnostic 3: File Check ---
echo "<div class='card'>";
echo "<h3>3. File Existence Check</h3>";
if (file_exists($targetFolder)) {
    $files = scandir($targetFolder);
    $files = array_diff($files, array('.', '..', '.gitignore'));
    echo "Found " . count($files) . " items in source.";
} else {
    echo "Source folder not found!";
}
echo "</div>";

echo "</div>";
