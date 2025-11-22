<?php require_once __DIR__ . '/../../layouts/auth_header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl mx-auto">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Add New Feedback</h1>
                <p class="text-gray-600 mt-2">Share feedback for your company</p>
            </div>
            <a href="/Job_poster/public/my-feedbacks"
                class="mt-4 md:mt-0 inline-block bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200">
                ← Back to Feedbacks
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['success']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['error']) ?></span>
            </div>
        <?php endif; ?>

        <!-- Feedback Form -->
        <form method="POST" action="/Job_poster/public/my-feedbacks/store" id="feedbackForm" class="space-y-4">
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="comments" class="block text-sm font-medium text-gray-700">Your Feedback</label>
                    <span class="text-sm text-gray-500">
                        <span id="wordCount">0</span> / 1000 words
                    </span>
                </div>
                <textarea id="comments" name="comments" rows="5" placeholder="Write your feedback here..." required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters allowed</p>
            </div>

            <div>
                <button type="submit" class="btn-submit" id="submitBtn">
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const textarea = document.getElementById('comments');
    const wordCountSpan = document.getElementById('wordCount');
    const submitBtn = document.getElementById('submitBtn');
    const MAX_WORDS = 1000;

    function updateWordCount() {
        const text = textarea.value;

        const chars = text.length;
        wordCountSpan.textContent = chars;

        if (chars > MAX_WORDS) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            textarea.classList.add('border-red-500');
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            textarea.classList.remove('border-red-500');
        }
    }

    textarea.addEventListener('input', updateWordCount);
    textarea.addEventListener('paste', () => {
        setTimeout(updateWordCount, 10);
    });

    document.getElementById('feedbackForm').addEventListener('submit', function (e) {
        const text = textarea.value;
        const chars = text.length;

        if (chars > MAX_WORDS) {
            e.preventDefault();
            alert(`Your feedback exceeds the limit of ${MAX_WORDS} characters. Current character count: ${chars}`);
            return false;
        }
    });
</script>