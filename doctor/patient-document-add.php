<?php
// clinic/patient-document-add.php
require_once '../core/init.php';
Auth::protect(['Clinic Admin', 'Doctor']);

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$patient_id = $_GET['id'] ?? '';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_patient_id = $_POST['patient_id'];
    $title = trim($_POST['title'] ?? 'Untitled Document');
    $category = $_POST['category'] ?? 'Other';
    $description = $_POST['description'];

    // Validation
    if (empty($target_patient_id)) $errors[] = "Patient ID is required.";
    if (empty($title)) $errors[] = "Document title is required.";
    if (!isset($_FILES['document']) || $_FILES['document']['error'] !== 0) $errors[] = "Please attach a file.";

    $input_id = trim($target_patient_id);
    $resolved_id = null;

    // 1. Try to match custom id_no first
    $stmt = $db->prepare("SELECT user_id FROM patient_profiles WHERE id_no = ?");
    $stmt->execute([$input_id]);
    $res = $stmt->fetch();
    if ($res) {
        $resolved_id = $res['user_id'];
    }

    // 2. If not found and starts with 'P', try stripping it (e.g. P000002 -> 2)
    if (!$resolved_id && stripos($input_id, 'P') === 0) {
        $numeric_id = ltrim(substr($input_id, 1), '0');
        $resolved_id = is_numeric($numeric_id) ? $numeric_id : null;
    }

    // 3. Fallback: try as raw numeric id
    if (!$resolved_id && is_numeric($input_id)) {
        $resolved_id = $input_id;
    }

    // Verify the resolved ID exists as a Patient
    $stmt = $db->prepare("SELECT id, clinic_id FROM users WHERE id = ? AND role_id = 3");
    $stmt->execute([$resolved_id]);
    $patient = $stmt->fetch();

    if (!$patient) {
        $errors[] = "Invalid Patient ID. We couldn't find a patient matching '" . e($input_id) . "'.";
    } else {
        $target_patient_id = $patient['id']; // Use the internal ID for DB insert
        // If doctor has a clinic_id, ensure it matches (Privacy Check)
        if (!empty($clinic_id) && $patient['clinic_id'] != $clinic_id) {
            $errors[] = "Security Alert: This patient belongs to another clinic.";
        }
    }

    if (empty($errors)) {
        try {
            // Handle File Upload
            $file = $_FILES['document'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $filename = 'doc_dr_' . $target_patient_id . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $upload_dir = '../uploads/documents/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
                $file_url = 'uploads/documents/' . $filename;

                // Save to DB (Now with Title and Category)
                $stmt = $db->prepare("
                    INSERT INTO patient_documents (patient_id, clinic_id, title, category, file_url, description) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$target_patient_id, $clinic_id, $title, $category, $file_url, $description]);

                $success = "Document successfully uploaded to patient vault!";
                header("Refresh:1; url=records.php");
            }
        } catch (Exception $e) {
            $errors[] = "Upload failed: " . $e->getMessage();
        }
    }
}

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>



<div class="space-y-6 animate-in fade-in duration-500">
    <!-- Header Area -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Add <span class="text-teal-600">Document</span></h2>
            <p class="text-slate-500 text-xs font-medium mt-1">Upload reports and clinical files to patient profiles.</p>
        </div>
        <a href="records.php" class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
            <i data-lucide="list" class="w-4 h-4"></i> Document List
        </a>
    </header>

    <?php if ($success): ?>
        <div class="bg-green-600 text-white p-4 rounded-xl font-bold shadow-lg shadow-green-600/20 flex items-center gap-3 text-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl text-xs font-bold space-y-1">
            <?php foreach ($errors as $e): ?>
                <p>• <?php echo $e; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm">
        <form method="POST" enctype="multipart/form-data" class="divide-y divide-slate-100">
            <div class="p-8 space-y-8">
                <!-- Row: Patient ID -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Patient ID <span class="text-red-500">*</span></label>
                    <div class="md:col-span-3">
                        <input type="text" name="patient_id" required value="<?php echo e($patient_id); ?>" placeholder="Enter Patient ID..." class="w-full bg-slate-50 border border-slate-100 px-5 py-3 rounded-xl focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all outline-none text-sm font-bold max-w-md">
                    </div>
                </div>

                <!-- Row: Title & Category -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Document Details <span class="text-red-500">*</span></label>
                    <div class="md:col-span-3 flex flex-col md:flex-row gap-4">
                        <input type="text" name="title" required placeholder="Document Title (e.g., Blood Test Report)" class="flex-1 bg-slate-50 border border-slate-100 px-5 py-3 rounded-xl focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all outline-none text-sm font-bold">
                        <select name="category" required class="bg-slate-50 border border-slate-100 px-5 py-3 rounded-xl focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all outline-none text-sm font-bold">
                            <option value="Lab Report">Lab Report</option>
                            <option value="Prescription">Prescription</option>
                            <option value="Imaging/X-Ray">Imaging/X-Ray</option>
                            <option value="Discharge Summary">Discharge Summary</option>
                            <option value="Consultation Note">Consultation Note</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Row: Attach File -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Attach File <span class="text-red-500">*</span></label>
                    <div class="md:col-span-3">
                        <div class="flex items-center gap-4">
                            <label class="cursor-pointer bg-teal-50 text-teal-600 px-4 py-2 rounded-lg font-bold text-xs hover:bg-teal-100 transition-all flex items-center gap-2 border border-teal-100">
                                <i data-lucide="upload-cloud" class="w-4 h-4"></i> Choose File
                                <input type="file" name="document" required class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                            </label>
                            <span id="file-name" class="text-xs font-bold text-slate-400">No file chosen</span>
                        </div>
                        <p class="text-[9px] text-slate-400 font-medium mt-2">Supported: PDF, JPG, PNG (Max 5MB)</p>
                    </div>
                </div>

                <!-- Row: Description -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest mt-3">Internal Notes</label>
                    <div class="md:col-span-3">
                        <textarea name="description" placeholder="Optional description or private notes for the clinical record..." class="w-full bg-slate-50 border border-slate-100 px-5 py-3 rounded-xl focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all outline-none text-sm font-bold min-h-[120px]"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50/50 px-8 py-4 flex items-center gap-3">
                <button type="reset" class="bg-white border border-slate-200 text-slate-600 px-8 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-50 transition-all shadow-sm">Reset Form</button>
                <div class="text-slate-300 text-xs font-bold italic px-1">or</div>
                <button type="submit" class="bg-teal-600 text-white px-10 py-2.5 rounded-xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i> Upload Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
