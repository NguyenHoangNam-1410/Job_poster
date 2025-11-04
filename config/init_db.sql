USE job_poster;

-- Users
INSERT INTO USERS (Email, Password, Role, Name, Avatar, is_active)
VALUES
('alice@example.com', 'hashed_pw1', 'Customer', 'Alice Nguyen', NULL, 1),
('bob@example.com', 'hashed_pw2', 'Admin', 'Bob Tran', NULL, 1),
('chris@example.com', 'hashed_pw3', 'Employer', 'Chris Le', NULL, 1),
('diana@example.com', 'hashed_pw4', 'Employer', 'Diana Pham', NULL, 1),
('edward@example.com', 'hashed_pw5', 'Employer', 'Edward Vu', NULL, 1),
('fiona@example.com', 'hashed_pw6', 'Customer', 'Fiona Huynh', NULL, 1),
('george@example.com', 'hashed_pw7', 'Admin', 'George Dao', NULL, 1),
('hannah@example.com', 'hashed_pw8', 'Employer', 'Hannah Trinh', NULL, 1);

-- Employers
INSERT INTO EMPLOYERS (user_id, company_name, website, logo, contact_phone, contact_email, contact_person, description)
VALUES
(3, 'TechNova Co.', 'https://www.technova.vn', NULL, '0901234567', 'hr@technova.vn', 'Chris Le', 'Innovative software development firm.'),
(4, 'CreativeHub Studio', 'https://creativehub.vn', NULL, '0912345678', 'contact@creativehub.vn', 'Diana Pham', 'Digital design and branding agency.'),
(5, 'GreenWorks Ltd.', 'https://greenworks.vn', NULL, '0923456789', 'jobs@greenworks.vn', 'Edward Vu', 'Sustainability consulting and green tech solutions.'),
(8, 'EduLink Global', 'https://edulink.vn', NULL, '0934567890', 'hr@edulink.vn', 'Hannah Trinh', 'Online education and e-learning platform.');

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

-- Jobs
INSERT INTO JOBS (employer_id, posted_by, title, location, description, requirements, salary, deadline, status)
VALUES
(1, 3, 'Backend Developer', 'Ho Chi Minh City', 'Develop REST APIs using Node.js and PostgreSQL.', '3+ years experience with Node.js, Git, Docker.', 25000000.00, '2025-12-31 23:59:59', 'open'),
(1, 3, 'Frontend Developer', 'Remote', 'Develop web UIs using React.js.', '2+ years in React, knowledge of Redux.', 22000000.00, '2025-12-20 23:59:59', 'open'),
(2, 4, 'UI/UX Designer', 'Da Nang', 'Design modern and intuitive web/mobile interfaces.', 'Experience with Figma, Adobe XD, responsive design.', 18000000.00, '2025-11-30 23:59:59', 'open'),
(2, 4, 'Digital Marketer', 'Hanoi', 'Manage SEO, Google Ads, and content marketing.', '1+ year marketing experience, basic analytics skills.', 15000000.00, '2025-12-15 23:59:59', 'open'),
(3, 5, 'Data Analyst', 'Remote', 'Analyze datasets and create visual dashboards.', 'Proficiency in Python, SQL, Power BI.', 27000000.00, '2025-12-25 23:59:59', 'paused'),
(3, 5, 'Project Manager', 'Ho Chi Minh City', 'Lead project execution and cross-team communication.', '5+ years PM experience, PMP certified preferred.', 30000000.00, '2025-11-20 23:59:59', 'open'),
(4, 8, 'Online Instructor', 'Remote', 'Teach computer science topics via EduLink platform.', 'Bachelor in CS, strong communication skills.', 20000000.00, '2025-12-10 23:59:59', 'draft'),
(4, 8, 'Customer Support Specialist', 'Hanoi', 'Assist students with technical issues and onboarding.', 'Good English, problem-solving skills.', 12000000.00, '2025-11-25 23:59:59', 'open');

-- Job-Category Mapping
INSERT INTO JOB_CATEGORY_MAP (job_id, category_id)
VALUES
(1, 1), (2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 5),
(7, 7),
(8, 8);

-- Staff Actions
INSERT INTO STAFF_ACTIONS (user_id, job_id, action_type, action_note)
VALUES
(2, 1, 'Review', 'Checked job posting details, approved listing.'),
(2, 2, 'Approve', 'Approved after QA check.'),
(7, 3, 'Reject', 'Incomplete description, requested revision.'),
(7, 4, 'Review', 'Verified marketing job details.'),
(2, 5, 'Pause', 'Paused due to salary update request.'),
(7, 6, 'Approve', 'All requirements valid, approved.'),
(2, 8, 'Close', 'Closed after hiring completed.');

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
