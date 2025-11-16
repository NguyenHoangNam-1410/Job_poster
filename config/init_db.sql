USE job_poster;

-- Users
INSERT INTO `users` (`UID`, `Email`, `Password`, `Role`, `Name`, `Avatar`, `auth_provider`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'alice@example.com', 'hashed_pw1', 'Staff', 'Alice Nguyen', '/Job_poster/public/image/avatar/1/avatar_1763266714.png', 'local', 1, '2025-11-05 13:08:06', '2025-11-16 04:18:34'),
(2, 'bob@example.com', 'hashed_pw2', 'Admin', 'Bob Tran', '/Job_poster/public/image/avatar/2/avatar_1763267198.png', 'local', 1, '2025-11-05 13:08:06', '2025-11-16 04:26:38'),
(3, 'chris@example.com', 'hashed_pw3', 'Employer', 'Chris Le', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(4, 'diana@example.com', 'hashed_pw4', 'Employer', 'Diana Pham', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(5, 'edward@example.com', 'hashed_pw5', 'Employer', 'Edward Vu', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(6, 'fiona@example.com', 'hashed_pw6', 'Staff', 'Fiona Huynh', '/Job_poster/public/image/avatar/6/avatar_1763265481.jpg', 'local', 1, '2025-11-05 13:08:06', '2025-11-16 03:58:01'),
(7, 'george@example.com', 'hashed_pw7', 'Admin', 'George Dao', '/Job_poster/public/image/avatar/7/avatar_1763268812.png', 'local', 1, '2025-11-05 13:08:06', '2025-11-16 04:53:32'),
(8, 'hannah@example.com', 'hashed_pw8', 'Employer', 'Hannah Trinh', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(9, 'ivan@example.com', 'hashed_pw9', 'Employer', 'Ivan Bui', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(10, 'jasmine@example.com', 'hashed_pw10', 'Employer', 'Jasmine Ngo', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(11, 'kevin@example.com', 'hashed_pw11', 'Employer', 'Kevin Truong', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(12, 'linda@example.com', 'hashed_pw12', 'Employer', 'Linda Ho', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(13, 'michael@example.com', 'hashed_pw13', 'Employer', 'Michael Dang', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(14, 'natalie@example.com', 'hashed_pw14', 'Employer', 'Natalie Phan', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(15, 'oliver@example.com', 'hashed_pw15', 'Employer', 'Oliver Vo', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(16, 'phoebe@example.com', 'hashed_pw16', 'Employer', 'Phoebe Dinh', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(17, 'quentin@example.com', 'hashed_pw17', 'Employer', 'Quentin Lam', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(18, 'rachel@example.com', 'hashed_pw18', 'Employer', 'Rachel Le', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(19, 'sam@example.com', 'hashed_pw19', 'Employer', 'Sam Nguyen', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(20, 'tina@example.com', 'hashed_pw20', 'Employer', 'Tina Tran', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(21, 'unknown@example.com', 'hashed_pw21', 'Employer', 'Unknown User', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(22, 'nguyen@example.com', 'hashed_pw22', 'Employer', 'Nguyen Van A', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(23, 'test_1763129661@example.com', '$2y$10$WbttonZgG9WYXWqTp7LWaeI.uhxXpx1pU.cIraFrzqjEhiRXN8Flq', 'Employer', 'Test User', NULL, 'local', 1, '2025-11-14 14:14:21', '2025-11-14 14:14:21'),
(24, 'silversoul66666@gmail.com', '$2y$10$FJwn.z8GWZOqwx.7A0DGZ.niTr2PrFPjO1j/D3GcXW3I1JuSYpExS', 'Employer', 'Nguyen Hoang Nam', NULL, 'local', 1, '2025-11-14 14:14:57', '2025-11-14 14:14:57');

-- Employers
INSERT INTO EMPLOYERS (user_id, company_name, website, logo, contact_phone, contact_email, contact_person, description)
VALUES
(3, 'TechNova Co., Ltd', 'https://technova.vn', NULL, '0901234567', 'contact@technova.vn', 'Chris Le', 'Innovative software company based in Ho Chi Minh City.'),
(4, 'Designify Studio', 'https://designify.vn', NULL, '0902345678', 'hello@designify.vn', 'Diana Pham', 'Creative design and branding agency in Da Nang.'),
(5, 'DataSense Analytics', 'https://datasense.vn', NULL, '0903456789', 'info@datasense.vn', 'Edward Vu', 'Data analytics and business intelligence solutions provider in Hanoi.'),
(8, 'EduLink Academy', 'https://edulink.vn', NULL, '0904567890', 'support@edulink.vn', 'Hannah Trinh', 'E-learning and training platform helping students learn tech skills.'),
(9, 'InternHub Vietnam', 'https://internhub.vn', NULL, '0905678901', 'hr@internhub.vn', 'Ivan Bui', 'Connecting students with tech internships across Vietnam.'),
(10, 'UXLabs Digital', 'https://uxlabs.vn', NULL, '0906789012', 'contact@uxlabs.vn', 'Jasmine Ngo', 'User experience and digital product design consultancy.'),
(11, 'PeopleFirst HR', 'https://peoplefirst.vn', NULL, '0907890123', 'info@peoplefirst.vn', 'Kevin Truong', 'Human resources and staffing solutions provider.'),
(12, 'FinanceWorks Asia', 'https://financeworks.vn', NULL, '0908901234', 'support@financeworks.vn', 'Linda Ho', 'Financial consulting and accounting technology services.'),
(13, 'CloudOps Tech', 'https://cloudops.vn', NULL, '0909012345', 'info@cloudops.vn', 'Michael Dang', 'Cloud infrastructure and DevOps automation specialists.'),
(14, 'SecureNet Solutions', 'https://securenet.vn', NULL, '0910123456', 'admin@securenet.vn', 'Natalie Phan', 'Cybersecurity and IT protection services for enterprises.'),
(15, 'DocuTech Writing', 'https://docutech.vn', NULL, '0911234567', 'contact@docutech.vn', 'Oliver Vo', 'Technical writing and documentation outsourcing agency.'),
(16, 'SEOBoost Agency', 'https://seoboost.vn', NULL, '0912345678', 'hello@seoboost.vn', 'Phoebe Dinh', 'Digital marketing and SEO optimization agency.'),
(17, 'Helpify Support', 'https://helpify.vn', NULL, '0913456789', 'help@helpify.vn', 'Quentin Lam', 'Remote customer service and support outsourcing team.'),
(18, 'OpsMaster Co.', 'https://opsmaster.vn', NULL, '0914567890', 'info@opsmaster.vn', 'Rachel Le', 'Operations management and process optimization consultancy.'),
(19, 'TechStart Inc.', 'https://techstart.vn', NULL, '0915678901', 'contact@techstart.vn', 'Sam Nguyen', 'Startup incubator and accelerator for tech entrepreneurs.');

-- Job Categories
INSERT INTO JOB_CATEGORIES (category_name)
VALUES
('Software Engineering'),
('Graphic Design'),
('Marketing'),
('Data Science'),
('Project Management'),
('Human Resources'),
('Education'),
('Customer Support');

-- Jobs (employer_id references EMPLOYERS.id which are auto-generated 1-15)
INSERT INTO JOBS (employer_id, posted_by, title, location, description, requirements, salary, deadline, status)
VALUES
(1, 3, 'Backend Developer', 'Ho Chi Minh City', 'Develop REST APIs using Node.js and PostgreSQL.', '3+ years experience with Node.js, Git, Docker.', 25000000.00, '2025-12-31 23:59:59', 'approved'),
(1, 3, 'Frontend Developer', 'Remote', 'Develop web UIs using React.js.', '2+ years in React, knowledge of Redux.', 22000000.00, '2025-12-20 23:59:59', 'approved'),
(2, 4, 'UI/UX Designer', 'Da Nang', 'Design modern and intuitive web/mobile interfaces.', 'Experience with Figma, Adobe XD, responsive design.', 18000000.00, '2025-11-30 23:59:59', 'approved'),
(2, 4, 'Digital Marketer', 'Hanoi', 'Manage SEO, Google Ads, and content marketing.', '1+ year marketing experience, basic analytics skills.', 15000000.00, '2025-12-15 23:59:59', 'approved'),
(3, 5, 'Data Analyst', 'Remote', 'Analyze datasets and create visual dashboards.', 'Proficiency in Python, SQL, Power BI.', 27000000.00, '2025-12-25 23:59:59', 'pending'),
(3, 5, 'Project Manager', 'Ho Chi Minh City', 'Lead project execution and cross-team communication.', '5+ years PM experience, PMP certified preferred.', 30000000.00, '2025-11-20 23:59:59', 'approved'),
(4, 8, 'Online Instructor', 'Remote', 'Teach computer science topics via EduLink platform.', 'Bachelor in CS, strong communication skills.', 20000000.00, '2025-12-10 23:59:59', 'draft'),
(4, 8, 'Customer Support Specialist', 'Hanoi', 'Assist students with technical issues and onboarding.', 'Good English, problem-solving skills.', 12000000.00, '2025-11-25 23:59:59', 'overdue'),
(5, 9, 'Mobile App Developer', 'Ho Chi Minh City', 'Build Android and iOS apps using Flutter.', '2+ years Flutter experience, REST API integration.', 24000000.00, '2025-12-28 23:59:59', 'approved'),
(5, 9, 'QA Tester', 'Remote', 'Design and execute test cases for web applications.', 'Experience in manual & automated testing.', 18000000.00, '2025-12-05 23:59:59', 'pending'),
(6, 10, 'DevOps Engineer', 'Da Nang', 'Maintain CI/CD pipelines and cloud deployments.', 'Strong Docker, Kubernetes, and AWS skills.', 32000000.00, '2025-12-31 23:59:59', 'approved'),
(6, 10, 'Security Analyst', 'Hanoi', 'Monitor security threats and perform vulnerability assessments.', 'Knowledge of OWASP, SIEM tools, and incident response.', 28000000.00, '2025-11-28 23:59:59', 'pending'),
(7, 11, 'Content Writer', 'Remote', 'Write SEO-friendly articles for tech blogs.', 'Excellent English writing skills, basic SEO knowledge.', 13000000.00, '2025-11-22 23:59:59', 'draft'),
(7, 11, 'Graphic Designer', 'Ho Chi Minh City', 'Create marketing materials and social media designs.', 'Proficiency in Photoshop, Illustrator.', 16000000.00, '2025-12-12 23:59:59', 'approved'),
(8, 12, 'Network Administrator', 'Hanoi', 'Manage LAN/WAN systems and ensure uptime.', 'Cisco certification preferred, troubleshooting skills.', 25000000.00, '2025-12-18 23:59:59', 'approved'),
(8, 12, 'Technical Recruiter', 'Remote', 'Source and screen technical candidates for IT roles.', 'Experience with ATS systems and interviewing.', 21000000.00, '2025-12-09 23:59:59', 'pending'),
(9, 13, 'Game Developer', 'Ho Chi Minh City', 'Develop mobile games using Unity and C#.', '1+ year Unity experience, teamwork skills.', 23000000.00, '2025-12-30 23:59:59', 'approved'),
(9, 13, 'Game Designer', 'Remote', 'Design engaging gameplay systems and levels.', 'Creativity, knowledge of game mechanics.', 20000000.00, '2025-12-20 23:59:59', 'pending'),
(10, 14, 'AI Researcher', 'Hanoi', 'Develop and train deep learning models for NLP.', 'Strong in Python, TensorFlow/PyTorch, and research papers.', 40000000.00, '2026-01-15 23:59:59', 'approved'),
(10, 14, 'Data Engineer', 'Ho Chi Minh City', 'Build data pipelines and maintain warehouse systems.', 'Experience in ETL tools, SQL, and Python.', 35000000.00, '2025-12-27 23:59:59', 'approved'),
(11, 15, 'System Administrator', 'Remote', 'Monitor and maintain servers and network systems.', 'Knowledge of Linux/Windows Server environments.', 26000000.00, '2025-11-29 23:59:59', 'overdue'),
(11, 15, 'Business Analyst', 'Da Nang', 'Bridge business requirements with technical teams.', 'Analytical skills, UML, and communication abilities.', 28000000.00, '2025-12-22 23:59:59', 'pending'),
(12, 16, 'IT Support Specialist', 'Hanoi', 'Provide technical assistance to clients and employees.', 'Customer service mindset, hardware/software troubleshooting.', 15000000.00, '2025-11-26 23:59:59', 'approved'),
(12, 16, 'Database Administrator', 'Ho Chi Minh City', 'Manage SQL databases and optimize queries.', '3+ years with MySQL/PostgreSQL.', 30000000.00, '2025-12-17 23:59:59', 'approved'),
(13, 17, 'Backend Intern', 'Da Nang', 'Assist in backend development tasks using Node.js.', 'Basic JavaScript knowledge, willingness to learn.', 8000000.00, '2025-12-10 23:59:59', 'rejected'),
(13, 17, 'Product Designer', 'Remote', 'Collaborate with product teams to create user-centered designs.', 'Portfolio with UI/UX examples, Figma expertise.', 19000000.00, '2025-11-18 23:59:59', 'rejected'),
(14, 18, 'HR Coordinator', 'Hanoi', 'Support recruitment and employee engagement initiatives.', 'Strong organizational and communication skills.', 16000000.00, '2025-11-24 23:59:59', 'soft_deleted'),
(14, 18, 'Finance Officer', 'Ho Chi Minh City', 'Prepare financial statements and manage transactions.', 'Degree in finance or accounting.', 23000000.00, '2025-12-14 23:59:59', 'soft_deleted'),
(15, 19, 'Cloud Engineer', 'Remote', 'Deploy and maintain cloud infrastructure on AWS.', 'AWS certification, Terraform, CI/CD knowledge.', 35000000.00, '2026-01-10 23:59:59', 'pending'),
(15, 19, 'Cybersecurity Engineer', 'Da Nang', 'Implement and monitor cybersecurity policies.', 'Experience in network security, firewalls, and IDS.', 37000000.00, '2025-12-19 23:59:59', 'approved'),
(1, 3, 'Technical Writer', 'Remote', 'Document APIs, SDKs, and developer tools.', 'Excellent writing and technical understanding.', 18000000.00, '2025-12-09 23:59:59', 'approved'),
(2, 4, 'SEO Specialist', 'Hanoi', 'Optimize website content for search engines.', 'Proven SEO track record, keyword research.', 17000000.00, '2025-12-11 23:59:59', 'rejected');

-- Job-Category Mapping
INSERT INTO JOB_CATEGORY_MAP (job_id, category_id)
VALUES
(1, 1), (1, 6),
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 5),
(7, 7),
(8, 8),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 2),
(14, 2),
(15, 1),
(16, 6),
(17, 1),
(18, 1),
(19, 4),
(20, 4),
(21, 1),
(22, 5),
(23, 6),
(24, 4),
(25, 1),
(26, 2),
(27, 6),
(28, 5),
(29, 1),
(30, 1),
(31, 1),
(32, 3);

-- Staff Actions
INSERT INTO STAFF_ACTIONS (user_id, job_id, action_type, action_date)
VALUES
(2, 1, 'Review', '2025-10-01 10:15:00'),
(2, 2, 'Approve', '2025-10-02 14:22:00'),
(7, 3, 'Reject', '2025-10-03 09:00:00'),
(7, 4, 'Review', '2025-10-05 11:45:00'),
(2, 5, 'Update', '2025-10-06 16:30:00'),
(7, 6, 'Approve', '2025-10-07 08:20:00'),
(2, 8, 'Close', '2025-10-08 13:40:00');

-- Feedback from users

INSERT INTO FEEDBACK (user_id, comments, created_at)
VALUES
(1, 'The job application process was smooth and intuitive. Great platform!', '2025-10-01 10:15:00'),
(2, 'We should improve moderation speed for new job postings.', '2025-10-02 14:22:00'),
(3, 'Posting jobs was easy, but I’d love to have analytics on applicant activity.', '2025-10-03 09:00:00'),
(4, 'Nice UI, but notifications for applicants could be improved.', '2025-10-05 11:45:00'),
(5, 'Had trouble editing job details after publishing, please check.', '2025-10-06 16:30:00'),
(6, 'Found a great job quickly, thank you for this service!', '2025-10-07 08:20:00'),
(7, 'The admin dashboard works well, but filtering logs could be faster.', '2025-10-08 13:40:00'),
(8, 'EduLink integration looks promising. Keep it up!', '2025-10-09 18:10:00'),
(1, 'Could you add a dark mode option? It would be great for late-night use.', '2025-10-10 22:15:00'),
(4, 'Love the design tools integration with Figma previews.', '2025-10-11 09:55:00');

INSERT INTO JOB_REVIEWS (job_id, reviewed_by, action, reason, created_at)
VALUES
(1, 2, 'approve', 'Job posting meets all requirements. Approved for public listing.', '2025-10-01 10:00:00'),
(2, 2, 'reject', 'Please include expected salary range and clarify remote policy.', '2025-10-02 09:30:00'),
(3, 7, 'approve', 'Design role verified. Portfolio link provided.', '2025-10-03 15:15:00'),
(4, 7, 'reject', 'Add KPIs or performance metrics to description before approval.', '2025-10-04 11:20:00'),
(5, 2, 'approve', 'Excellent detail on requirements and tools. Approved.', '2025-10-05 13:40:00'),
(6, 7, 'reject', 'Missing PMP certification info, please update.', '2025-10-06 08:30:00'),
(6, 2, 'approve', 'Updated requirements received. Approved for posting.', '2025-10-07 10:45:00'),
(8, 2, 'approve', 'All fields complete. Approved for listing.', '2025-10-08 14:10:00');

UPDATE JOBS SET status = 'approved' WHERE id IN (1, 2, 3, 4, 6);
UPDATE JOBS SET status = 'pending' WHERE id = 5;
UPDATE JOBS SET status = 'draft' WHERE id = 7;
UPDATE JOBS SET status = 'overdue' WHERE id = 8;
