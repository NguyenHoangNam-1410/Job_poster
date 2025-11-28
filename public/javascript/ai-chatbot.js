/**
 * AI Chatbot Component for Worknest
 */
class AIChatbot {
    constructor(options = {}) {
        this.container = null;
        this.window = null;
        this.isOpen = false;
        this.context = options.context || {}; // Can contain job_id, etc.
        this.apiBase = options.apiBase || '/Worknest/public/api/ai';
        this.messages = [];
        // Avatar paths - update these to your actual image paths
        this.avatarPaths = options.avatarPaths || [
            '/Worknest/public/images/chatbot-avatars/avatar-1.png',
            '/Worknest/public/images/chatbot-avatars/avatar-2.png',
            '/Worknest/public/images/chatbot-avatars/avatar-3.png',
            '/Worknest/public/images/chatbot-avatars/avatar-4.png',
            '/Worknest/public/images/chatbot-avatars/avatar-5.png'
        ];
        this.currentAvatar = null; // Will be set when chat opens
        // User avatar from session (passed from PHP)
        this.userAvatar = options.userAvatar || (typeof window !== 'undefined' && window.WORKNEST_USER_AVATAR) || null;
        this.initialize();
    }

    initialize() {
        this.createHTML();
        this.attachEvents();
        this.loadInitialMessage();
    }

    createHTML() {
        // Create container
        this.container = document.createElement('div');
        this.container.className = 'ai-chatbot-container';
        this.container.innerHTML = `
            <button class="ai-chatbot-toggle-tab" id="ai-chatbot-toggle">
                <span class="ai-chatbot-toggle-icon">💬</span>
                <span class="ai-chatbot-toggle-label">AI</span>
            </button>
            <div class="ai-chatbot-sidebar" id="ai-chatbot-window">
                <div class="ai-chatbot-header">
                    <div class="ai-chatbot-header-title">Worknest AI Assistant</div>
                    <button class="ai-chatbot-close" id="ai-chatbot-close">×</button>
                </div>
                <div class="ai-chatbot-messages" id="ai-chatbot-messages"></div>
                <div class="ai-chatbot-input-area">
                    <div class="ai-chatbot-suggestions" id="ai-chatbot-suggestions"></div>
                    <div class="ai-chatbot-input-wrapper">
                        <textarea 
                            class="ai-chatbot-input" 
                            id="ai-chatbot-input" 
                            placeholder="Ask me anything"
                            rows="1"
                        ></textarea>
                        <button class="ai-chatbot-send" id="ai-chatbot-send">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(this.container);
        
        this.window = document.getElementById('ai-chatbot-window');
        this.input = document.getElementById('ai-chatbot-input');
    }
    

    attachEvents() {
        // Toggle button
        document.getElementById('ai-chatbot-toggle').addEventListener('click', () => {
            this.toggle();
        });

        // Close button
        document.getElementById('ai-chatbot-close').addEventListener('click', () => {
            this.close();
        });

        // Send button
        document.getElementById('ai-chatbot-send').addEventListener('click', () => {
            this.sendMessage();
        });

        // Enter key to send (Shift+Enter for new line)
        this.input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });

        // Auto-resize textarea
        this.input.addEventListener('input', () => {
            this.input.style.height = 'auto';
            this.input.style.height = Math.min(this.input.scrollHeight, 120) + 'px';
        });

    }

    loadInitialMessage() {
        // Select initial avatar if not already selected
        if (!this.currentAvatar) {
            this.selectRandomAvatar();
        }
        
        const suggestions = this.getSuggestions();
        this.showSuggestions(suggestions);
        
        // Show welcome message based on context
        let welcomeMessage = '👋 Hi! I\'m Worknest AI Assistant. I can help you:\n\n';
        welcomeMessage += '• Search for suitable jobs\n';
        
        if (this.context.job_id) {
            welcomeMessage += '• Summarize this job posting\n';
            welcomeMessage += '• Answer questions about this job\n';
        }
        
        welcomeMessage += '• Get career advice\n\n';
        welcomeMessage += 'Try one of the suggestions below or ask me anything! I can understand both English and Vietnamese.';
        
        this.addMessage('ai', welcomeMessage);
    }

    getSuggestions() {
        if (this.context.job_id) {
            // Job detail page suggestions
            return [
                'Summarize this job',
                'What are the main requirements?',
                'What skills am I missing?',
                'What is the salary and benefits?',
                'Tell me more about this company'
            ];
        } else {
            // General job search suggestions
            return [
                'Find remote jobs',
                'IT jobs in Ho Chi Minh City',
                'Part-time jobs for students',
                'Data science positions',
                'Marketing jobs'
            ];
        }
    }
    
    getContextualSuggestions(lastMessage, lastResponse) {
        // Generate smart suggestions based on conversation context
        const suggestions = [];
        const lowerMessage = lastMessage ? lastMessage.toLowerCase() : '';
        const lowerResponse = lastResponse ? lastResponse.toLowerCase() : '';
        
        // If on job detail page
        if (this.context.job_id) {
            // If just summarized, suggest follow-up questions
            if (lowerMessage.includes('summarize') || lowerResponse.includes('overview')) {
                return [
                    'What are the main requirements?',
                    'What skills am I missing?',
                    'What is the salary and benefits?',
                    'Tell me more about this company'
                ];
            }
            // If asked about requirements, suggest skills/salary
            if (lowerMessage.includes('requirement') || lowerResponse.includes('requirement')) {
                return [
                    'What skills am I missing?',
                    'What is the salary and benefits?',
                    'How do I apply?',
                    'Tell me more about this company'
                ];
            }
            // Default job suggestions
            return this.getSuggestions();
        }
        
        // General suggestions based on search results
        if (lowerResponse.includes('found') && lowerResponse.includes('job')) {
            return [
                'Show me remote jobs',
                'IT jobs in Ho Chi Minh City',
                'Part-time jobs for students',
                'Filter by salary'
            ];
        }
        
        // Default general suggestions
        return this.getSuggestions();
    }

    showSuggestions(suggestions) {
        const container = document.getElementById('ai-chatbot-suggestions');
        if (!container) return;
        
        container.innerHTML = '';
        container.style.display = 'flex'; // Ensure it's visible
        
        suggestions.forEach(suggestion => {
            const btn = document.createElement('button');
            btn.className = 'ai-chatbot-suggestion';
            btn.textContent = suggestion;
            btn.addEventListener('click', () => {
                this.input.value = suggestion;
                this.sendMessage();
            });
            container.appendChild(btn);
        });
    }
    
    updateSuggestions(suggestions) {
        // Update suggestions without hiding them
        this.showSuggestions(suggestions);
    }

    toggle() {
        this.isOpen = !this.isOpen;
        
        if (this.isOpen) {
            // Randomly select a new avatar each time chat opens
            this.selectRandomAvatar();
            this.window.classList.add('open');
            this.container.classList.add('sidebar-open');
            this.input.focus();
        } else {
            this.window.classList.remove('open');
            this.container.classList.remove('sidebar-open');
        }
    }

    open() {
        if (!this.isOpen) {
            this.isOpen = true;
            // Randomly select a new avatar each time chat opens
            this.selectRandomAvatar();
            this.window.classList.add('open');
            this.container.classList.add('sidebar-open');
            this.input.focus();
        }
    }
    
    selectRandomAvatar() {
        // Randomly select one avatar from the array
        const randomIndex = Math.floor(Math.random() * this.avatarPaths.length);
        this.currentAvatar = this.avatarPaths[randomIndex];
    }
    
    getAvatarHTML(role) {
        if (role === 'user') {
            // User avatar: use real avatar if available, otherwise emoji
            if (this.userAvatar) {
                return `<img src="${this.userAvatar}" alt="User" class="ai-chatbot-avatar-img">`;
            }
            return '👤';
        } else {
            // AI avatar uses image if available, otherwise fallback to emoji
            if (this.currentAvatar) {
                return `<img src="${this.currentAvatar}" alt="AI Assistant" class="ai-chatbot-avatar-img">`;
            }
            return '🤖';
        }
    }

    close() {
        if (this.isOpen) {
            this.isOpen = false;
            this.window.classList.remove('open');
            this.container.classList.remove('sidebar-open');
        }
    }

    addMessage(role, content, jobs = null) {
        const messagesContainer = document.getElementById('ai-chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `ai-chatbot-message ${role}`;
        
        const avatar = this.getAvatarHTML(role);
        const avatarClass = role === 'user' ? 'user' : 'ai';
        
        let jobsHTML = '';
        if (jobs && Array.isArray(jobs) && jobs.length > 0) {
            jobsHTML = '<div class="ai-chatbot-jobs-list">';
            jobs.forEach(job => {
                jobsHTML += `
                    <div class="ai-chatbot-job-item">
                        <a href="${job.url}" target="_blank">${this.escapeHtml(job.title)}</a>
                        <div class="job-meta">${this.escapeHtml(job.company)} • ${this.escapeHtml(job.location)}</div>
                    </div>
                `;
            });
            jobsHTML += '</div>';
        }
        
        messageDiv.innerHTML = `
            <div class="ai-chatbot-message-avatar ${avatarClass}">${avatar}</div>
            <div class="ai-chatbot-message-content">${this.formatMessage(content)}${jobsHTML}</div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        this.messages.push({ role, content, jobs });
    }

    formatMessage(text) {
        if (!text) return '';
        
        // Step 1: Convert literal \n strings to actual newlines
        text = text.replace(/\\n/g, '\n');
        
        // Step 1.5: Remove unwanted HTML tags that AI might add
        // Remove <s> and </s> tags (strikethrough tags that AI sometimes adds)
        text = text.replace(/<\/?s>/gi, '');
        text = text.replace(/<s\s*\/>/gi, ''); // Remove self-closing <s/>
        
        // Remove any other unwanted formatting tags
        text = text.replace(/<\/?span>/gi, '');
        text = text.replace(/<\/?div>/gi, '');
        
        // Step 2: Remove ALL ** markers (no bold formatting)
        text = text.replace(/\*\*/g, '');
        
        // Step 3: Escape HTML to prevent XSS
        text = this.escapeHtml(text);
        
        // Step 4: Split by double newlines to get sections
        const sections = text.split(/\n\n+/);
        const formattedSections = [];
        
        for (const section of sections) {
            const lines = section.trim().split('\n');
            let formattedSection = '';
            let inList = false;
            let listItems = [];
            
            for (let i = 0; i < lines.length; i++) {
                let line = lines[i].trim();
                if (!line) {
                    // Empty line closes list
                    if (inList && listItems.length > 0) {
                        formattedSection += '<ul class="ai-message-list">' + listItems.join('') + '</ul>';
                        listItems = [];
                        inList = false;
                    }
                    continue;
                }
                
                // Check for headers (must be at start of line)
                if (line.match(/^###\s+/)) {
                    if (inList) {
                        formattedSection += '<ul class="ai-message-list">' + listItems.join('') + '</ul>';
                        listItems = [];
                        inList = false;
                    }
                    let headerText = line.replace(/^###\s+/, '').trim();
                    formattedSection += '<h3 class="ai-message-h3">' + headerText + '</h3>';
                } else if (line.match(/^##\s+/)) {
                    if (inList) {
                        formattedSection += '<ul class="ai-message-list">' + listItems.join('') + '</ul>';
                        listItems = [];
                        inList = false;
                    }
                    let headerText = line.replace(/^##\s+/, '').trim();
                    formattedSection += '<h2 class="ai-message-h2">' + headerText + '</h2>';
                } else if (line.match(/^#\s+/)) {
                    if (inList) {
                        formattedSection += '<ul class="ai-message-list">' + listItems.join('') + '</ul>';
                        listItems = [];
                        inList = false;
                    }
                    let headerText = line.replace(/^#\s+/, '').trim();
                    formattedSection += '<h1 class="ai-message-h1">' + headerText + '</h1>';
                } 
                // Check for bullet points
                else if (line.match(/^[-•*]\s+/)) {
                    if (!inList) {
                        inList = true;
                    }
                    let content = line.replace(/^[-•*]\s+/, '').trim();
                    listItems.push('<li>' + content + '</li>');
                } 
                // Regular paragraph or line
                else {
                    if (inList) {
                        formattedSection += '<ul class="ai-message-list">' + listItems.join('') + '</ul>';
                        listItems = [];
                        inList = false;
                    }
                    formattedSection += '<p>' + line + '</p>';
                }
            }
            
            // Close any open list
            if (inList && listItems.length > 0) {
                formattedSection += '<ul class="ai-message-list">' + listItems.join('') + '</ul>';
            }
            
            if (formattedSection) {
                formattedSections.push(formattedSection);
            }
        }
        
        // If no formatting was applied, return as simple paragraphs
        const result = formattedSections.join('');
        if (!result || result.trim() === '') {
            // Fallback: just convert newlines to paragraphs
            const cleanText = text.replace(/\n\n+/g, '</p><p>').replace(/\n/g, '<br>');
            return '<p>' + cleanText + '</p>';
        }
        return result;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showTyping() {
        const messagesContainer = document.getElementById('ai-chatbot-messages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'ai-chatbot-message ai';
        typingDiv.id = 'ai-chatbot-typing';
        const avatarHTML = this.getAvatarHTML('ai');
        typingDiv.innerHTML = `
            <div class="ai-chatbot-message-avatar ai">${avatarHTML}</div>
            <div class="ai-chatbot-message-content">
                <div class="ai-chatbot-typing">
                    <div class="ai-chatbot-typing-dot"></div>
                    <div class="ai-chatbot-typing-dot"></div>
                    <div class="ai-chatbot-typing-dot"></div>
                </div>
            </div>
        `;
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    hideTyping() {
        const typing = document.getElementById('ai-chatbot-typing');
        if (typing) {
            typing.remove();
        }
    }

    async sendMessage() {
        const message = this.input.value.trim();
        if (!message) return;

        // Clear input
        this.input.value = '';
        this.input.style.height = 'auto';
        
        // Keep suggestions visible - don't hide them!

        // Add user message
        this.addMessage('user', message);

        // Show typing indicator
        this.showTyping();

        // Disable send button
        const sendBtn = document.getElementById('ai-chatbot-send');
        sendBtn.disabled = true;

        try {
            // Determine which API endpoint to use
            let endpoint = '/chat';
            let payload = {
                message: message,
                context: this.context
            };

            // If on job detail page, try to summarize first
            if (this.context.job_id && (message.toLowerCase().includes('tóm tắt') || message.toLowerCase().includes('summary'))) {
                endpoint = '/summarize-job';
                payload = { job_id: this.context.job_id };
            } 
            // If asking about a specific job
            else if (this.context.job_id) {
                endpoint = '/answer-job-question';
                payload = {
                    job_id: this.context.job_id,
                    question: message
                };
            }
            // Detect job search intent (more keywords)
            else if (
                message.toLowerCase().includes('tìm') || 
                message.toLowerCase().includes('kiếm') ||
                message.toLowerCase().includes('job') ||
                message.toLowerCase().includes('việc') ||
                message.toLowerCase().includes('tuyển') ||
                message.toLowerCase().match(/\b(data science|it|php|python|java|developer|designer|marketing|sales|accountant|nurse|teacher)\b/i)
            ) {
                endpoint = '/search-jobs';
                payload = { query: message, limit: 5 };
            }

            const response = await fetch(`${this.apiBase}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            this.hideTyping();

            if (data.success) {
                let aiResponse = '';
                
                if (endpoint === '/summarize-job') {
                    aiResponse = data.summary;
                    this.addMessage('ai', aiResponse);
                    // Update suggestions with contextual ones after summary
                    if (this.context.job_id) {
                        const contextual = this.getContextualSuggestions(message, aiResponse);
                        this.updateSuggestions(contextual);
                    }
                } else if (endpoint === '/answer-job-question') {
                    aiResponse = data.answer;
                    this.addMessage('ai', aiResponse);
                    // Update suggestions based on conversation context
                    if (this.context.job_id) {
                        const contextual = this.getContextualSuggestions(message, aiResponse);
                        this.updateSuggestions(contextual);
                    }
                } else if (endpoint === '/search-jobs') {
                    aiResponse = data.message;
                    this.addMessage('ai', aiResponse, data.jobs || []);
                    // Update suggestions for job search
                    const contextual = this.getContextualSuggestions(message, aiResponse);
                    this.updateSuggestions(contextual.length > 0 ? contextual : [
                        'Find remote jobs',
                        'IT jobs in Ho Chi Minh City',
                        'Part-time jobs for students',
                        'Show me more jobs'
                    ]);
                } else {
                    aiResponse = data.message || data.answer || data.summary || '';
                    this.addMessage('ai', aiResponse, data.jobs || []);
                    // Refresh suggestions based on context
                    const contextual = this.getContextualSuggestions(message, aiResponse);
                    this.updateSuggestions(contextual.length > 0 ? contextual : this.getSuggestions());
                }
            } else {
                // Show error message from server
                let errorMsg = data.message || data.answer || data.summary || 'Sorry, an error occurred. Please try again.';
                
                // Add helpful tips
                if (errorMsg.includes('API key') || errorMsg.includes('configure')) {
                    errorMsg += '\n\n💡 **Help:**\n1. Check if .env file contains OPENROUTER_API_KEY\n2. Visit test script: /Worknest/public/test-gemini-api.php\n3. See OPENROUTER_AI_SETUP.md for details';
                }
                
                this.addMessage('ai', errorMsg);
            }

        } catch (error) {
            console.error('Chatbot error:', error);
            this.hideTyping();
            
            let errorMsg = 'Sorry, an error occurred. ';
            
            if (error.message) {
                errorMsg += error.message;
            } else if (error instanceof TypeError && error.message.includes('fetch')) {
                errorMsg += 'Unable to connect to server. Please check your internet connection.';
            } else {
                errorMsg += 'Please try again later or check the console for error details.';
            }
            
            this.addMessage('ai', errorMsg + '\n\n💡 **Troubleshooting:**\n1. Check if API key is configured correctly\n2. Visit: /Worknest/public/test-gemini-api.php to test connection\n3. See OPENROUTER_AI_SETUP.md for setup guide');
        } finally {
            sendBtn.disabled = false;
        }
    }

    // Public method to set context (e.g., when on job detail page)
    setContext(context) {
        this.context = { ...this.context, ...context };
    }
}

// Initialize chatbot when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initChatbot);
} else {
    initChatbot();
}

function initChatbot() {
    // Detect if we're on a job detail page
    const jobIdMatch = window.location.pathname.match(/\/jobs\/show\/(\d+)/);
    const context = {};
    
    if (jobIdMatch) {
        context.job_id = parseInt(jobIdMatch[1]);
    }

        // Initialize chatbot with avatar paths and user avatar
    window.worknestAI = new AIChatbot({
        context: context,
        apiBase: '/Worknest/public/api/ai',
        avatarPaths: [
            '/Worknest/public/images/chatbot-avatars/avatar-1.png',
            '/Worknest/public/images/chatbot-avatars/avatar-2.png',
            '/Worknest/public/images/chatbot-avatars/avatar-3.png',
            '/Worknest/public/images/chatbot-avatars/avatar-4.png',
            '/Worknest/public/images/chatbot-avatars/avatar-5.png'
        ],
        userAvatar: typeof window !== 'undefined' && window.WORKNEST_USER_AVATAR ? window.WORKNEST_USER_AVATAR : null
    });
}
