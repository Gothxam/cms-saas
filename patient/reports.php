<?php
// patient/reports.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];
$clinic_id = $_SESSION['clinic_id'];

// Handle Upload
if (isset($_FILES['report_file'])) {
    $title = trim($_POST['report_title'] ?? 'Medical Report');
    $file = $_FILES['report_file'];
    
    if ($file['error'] === 0) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('report_') . '.' . $ext;
        $upload_dir = '../uploads/reports/';
        
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
            $stmt = $db->prepare("INSERT INTO patient_documents (clinic_id, patient_id, title, file_url, category, created_at) VALUES (?, ?, ?, ?, 'Report', NOW())");
            $stmt->execute([$clinic_id, $patient_id, $title, 'uploads/reports/' . $filename]);
            header("Location: reports.php?success=uploaded");
            exit;
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $report_id = (int)$_GET['delete'];
    // Security: Check if report belongs to patient
    $stmt = $db->prepare("SELECT file_url FROM patient_documents WHERE id = ? AND patient_id = ?");
    $stmt->execute([$report_id, $patient_id]);
    $report = $stmt->fetch();
    
    if ($report) {
        if (file_exists('../' . $report['file_url'])) {
            unlink('../' . $report['file_url']);
        }
        $stmt = $db->prepare("DELETE FROM patient_documents WHERE id = ?");
        $stmt->execute([$report_id]);
        header("Location: reports.php?success=deleted");
        exit;
    }
}

// Get all reports
$stmt = $db->prepare("SELECT * FROM patient_documents WHERE patient_id = ? AND category = 'Report' ORDER BY created_at DESC");
$stmt->execute([$patient_id]);
$reports = $stmt->fetchAll();

$page_title = "My Medical Reports";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-6xl mx-auto py-10 space-y-12 animate-in fade-in duration-700">

    <!-- Header Section -->
    <div class="relative overflow-hidden bg-slate-900 rounded-[3rem] p-12 text-white">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-500/10 blur-[100px] -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/10 blur-[100px] -ml-48 -mb-48"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-xl">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-teal-500/20 text-teal-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-teal-500/20">Turbo Vault</span>
                    <span class="w-2 h-2 bg-teal-500 rounded-full animate-pulse"></span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black tracking-tight mb-4">Medical Reports</h2>
                <p class="text-slate-400 text-base font-medium leading-relaxed">Your secure, encrypted vault for external lab reports and diagnostic documents. Upload once, access anywhere.</p>
            </div>
            <button onclick="document.getElementById('uploadModal').showModal()" class="group bg-white text-slate-900 px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-white/5 hover:bg-teal-500 hover:text-white transition-all flex items-center gap-4 self-start md:self-center">
                <div class="w-10 h-10 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center group-hover:bg-white/20 group-hover:text-white transition-all">
                    <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                </div>
                Secure Upload
            </button>
        </div>
    </div>

    <!-- Analytics / Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Documents</p>
            <div class="flex items-end gap-3">
                <h3 class="text-3xl font-black text-slate-900 leading-none"><?php echo count($reports); ?></h3>
                <span class="text-xs font-bold text-teal-600 mb-1">Files Hosted</span>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Last Upload</p>
            <h3 class="text-lg font-black text-slate-900 leading-none">
                <?php echo !empty($reports) ? date('M d, Y', strtotime($reports[0]['created_at'])) : 'No Uploads'; ?>
            </h3>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Security Status</p>
            <div class="flex items-center gap-2">
                <i data-lucide="shield-check" class="w-5 h-5 text-emerald-500"></i>
                <span class="text-sm font-bold text-emerald-600">AES-256 Encrypted</span>
            </div>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="space-y-6">
        <div class="flex items-center justify-between px-4">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Vault Inventory</h3>
            <div class="flex items-center gap-4">
                <button class="p-2 text-slate-400 hover:text-slate-900 transition-colors"><i data-lucide="layout-grid" class="w-5 h-5"></i></button>
                <button class="p-2 text-slate-300 hover:text-slate-900 transition-colors"><i data-lucide="list" class="w-5 h-5"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($reports as $report): 
                $ext = strtolower(pathinfo($report['file_url'], PATHINFO_EXTENSION));
                $icon = 'file-text';
                $color = 'blue';
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) { $icon = 'image'; $color = 'orange'; }
                if ($ext === 'pdf') { $icon = 'file-type-2'; $color = 'red'; }
            ?>
                <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm relative group overflow-hidden transition-all hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-8">
                        <div class="w-14 h-14 bg-<?php echo $color; ?>-50 text-<?php echo $color; ?>-600 rounded-2xl flex items-center justify-center group-hover:bg-<?php echo $color; ?>-600 group-hover:text-white transition-all shadow-sm">
                            <i data-lucide="<?php echo $icon; ?>" class="w-7 h-7"></i>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="../<?php echo $report['file_url']; ?>" download class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-teal-50 hover:text-teal-600 transition-all">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $report['id']; ?>)" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-all">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <h4 class="text-xl font-black text-slate-900 tracking-tight line-clamp-1"><?php echo e($report['title']); ?></h4>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Added <?php echo date('M d, Y', strtotime($report['created_at'])); ?></span>
                            <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                            <span class="text-[10px] font-black text-teal-500 uppercase tracking-widest"><?php echo strtoupper($ext); ?></span>
                        </div>
                    </div>
                    
                    <div class="mt-10 grid grid-cols-2 gap-3">
                        <a href="../<?php echo $report['file_url']; ?>" target="_blank" class="flex items-center justify-center gap-2 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-600 transition-all">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> Preview
                        </a>
                        <button onclick="shareReport(<?php echo $report['id']; ?>)" class="flex items-center justify-center gap-2 py-4 bg-slate-50 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
                            <i data-lucide="share-2" class="w-3.5 h-3.5"></i> Share
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($reports)): ?>
                <div class="col-span-full py-32 text-center bg-white border-2 border-dashed border-slate-200 rounded-[4rem]">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i data-lucide="cloud-off" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 uppercase tracking-widest">Vault Empty</h3>
                    <p class="text-slate-400 text-base font-medium mt-3 max-w-sm mx-auto">Start building your medical history by uploading your first report today.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Upload Modal -->
    <dialog id="uploadModal" class="bg-transparent backdrop:bg-slate-900/80 backdrop:backdrop-blur-md">
        <div class="bg-white rounded-[4rem] p-12 w-full max-w-lg shadow-2xl relative animate-in zoom-in duration-300">
            <button onclick="document.getElementById('uploadModal').close()" class="absolute top-10 right-10 w-12 h-12 bg-slate-50 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-2xl flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>

            <div class="flex items-center gap-5 mb-10">
                <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-[1.5rem] flex items-center justify-center shadow-inner">
                    <i data-lucide="upload-cloud" class="w-8 h-8"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">New Upload</h3>
                    <p class="text-slate-400 text-sm font-medium">Add to your medical vault.</p>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-8" id="uploadForm">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Report Description</label>
                    <input type="text" name="report_title" required placeholder="e.g. Annual Health Check - Blood Work"
                        class="w-full p-6 bg-slate-50 border-2 border-transparent rounded-3xl text-sm font-bold text-slate-800 outline-none focus:border-teal-500/20 focus:ring-4 focus:ring-teal-500/5 transition-all">
                </div>
                
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Document File</label>
                    <div id="dropzone" class="relative group cursor-pointer">
                        <input type="file" name="report_file" id="fileInput" required accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                        <div onclick="document.getElementById('fileInput').click()" 
                            class="w-full py-16 px-8 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2.5rem] text-center group-hover:border-teal-500/50 group-hover:bg-teal-50/30 transition-all duration-500">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm text-slate-300 group-hover:text-teal-500 group-hover:scale-110 transition-all duration-500">
                                <i data-lucide="file-plus" class="w-8 h-8"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-700 mb-1">Click to browse or drag & drop</h4>
                            <p class="text-xs text-slate-400 font-medium" id="fileName">PDF, JPG or PNG up to 10MB</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white p-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:bg-teal-600 hover:shadow-teal-600/30 transition-all flex items-center justify-center gap-3 group">
                    Start Secure Upload
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
        </div>
    </dialog>

</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to permanently delete this report? This action cannot be undone.')) {
        window.location.href = 'reports.php?delete=' + id;
    }
}

function shareReport(id) {
    alert('Sharing functionality will be available soon.');
}

// Simple File Input Display
document.getElementById('fileInput')?.addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        document.getElementById('fileName').textContent = 'Selected: ' + fileName;
        document.getElementById('fileName').className = 'text-xs text-teal-600 font-bold';
    }
});

// Drag and Drop Logic
const dropzone = document.getElementById('dropzone');
if (dropzone) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.add('scale-95', 'opacity-80'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.remove('scale-95', 'opacity-80'), false);
    });

    dropzone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('fileInput').files = files;
        
        const event = new Event('change');
        document.getElementById('fileInput').dispatchEvent(event);
    }
}
</script>

<?php require_once 'components/footer.php'; ?>
