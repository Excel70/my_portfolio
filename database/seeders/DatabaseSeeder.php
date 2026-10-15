<?php

namespace Database\Seeders;

use App\Models\Design;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'name' => 'Aderinlewo Excel Olamilekan', 'title' => 'Software Engineer & Digital Product Builder',
            'about' => "I'm a technology enthusiast with a strong interest in software engineering, web development, UI/UX design, graphic design, and emerging Web3 technologies.\n\nI enjoy transforming ideas into functional and visually engaging digital products. My approach combines technical problem-solving with thoughtful design to create solutions that are practical, responsive, and easy to use.\n\nBeyond development, I'm interested in technical writing, project management, and the evolving possibilities of blockchain and Web3. I'm continuously learning, experimenting with new technologies, and working on projects that challenge me to grow as a technology professional.",
            'email' => '', 'github_url' => '', 'linkedin_url' => '', 'whatsapp_url' => '',
        ];
        foreach ($settings as $key => $value) { Setting::updateOrCreate(['key' => $key], ['value' => $value]); }

        $projects = [
            ['title' => 'Student Complaint Management System', 'short_description' => 'A centralized digital platform designed to help students submit, track and manage complaints while enabling administrators and relevant staff to handle cases efficiently.', 'category' => 'Web Development', 'technologies' => ['HTML5', 'CSS3', 'JavaScript', 'PHP']],
            ['title' => 'Result Management System', 'short_description' => 'A digital academic result management platform designed to simplify the process of recording, managing, calculating and presenting student academic results.', 'category' => 'Software Engineering', 'technologies' => ['PHP', 'MySQL', 'JavaScript']],
            ['title' => 'Web3 Project', 'short_description' => 'A project exploring blockchain and Web3 technologies with a focus on decentralized digital experiences and emerging technology.', 'category' => 'Web3', 'technologies' => ['Web3', 'Blockchain']],
            ['title' => 'Graphic Design Collection', 'short_description' => 'A collection of selected graphic design work demonstrating creativity, visual communication, branding and digital design.', 'category' => 'Graphic Design', 'technologies' => ['Graphic Design']],
        ];
        foreach ($projects as $order => $projectData) {
            $project = Project::updateOrCreate(['slug' => Str::slug($projectData['title'])], ['title' => $projectData['title'], 'short_description' => $projectData['short_description'], 'category' => $projectData['category'], 'status' => 'published', 'featured' => $order < 3, 'display_order' => $order]);
            $ids = collect($projectData['technologies'])->map(fn ($name) => Technology::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])->id);
            $project->technologies()->sync($ids);
        }

        $skillGroups = [
            'Development' => ['HTML5', 'CSS3', 'JavaScript', 'Web Development', 'Software Engineering', 'Responsive Design'],
            'Design' => ['UI/UX Design', 'Graphic Design'], 'Technology' => ['Web3', 'Blockchain', 'Cryptocurrency'],
            'Professional' => ['Technical Writing', 'Project Management', 'Problem Solving', 'Research', 'Documentation'],
        ];
        $order = 0; foreach ($skillGroups as $category => $skills) { foreach ($skills as $skill) { Skill::updateOrCreate(['name' => $skill], ['category' => $category, 'status' => 'active', 'display_order' => $order++]); } }
        foreach (['Web Development', 'UI/UX Design', 'Software Engineering', 'Graphic Design', 'Web3 & Blockchain Exploration', 'Technical Writing', 'Project Management'] as $order => $service) { Service::updateOrCreate(['title' => $service], ['description' => 'Details coming soon.', 'status' => 'active', 'display_order' => $order]); }
        Design::firstOrCreate(['title' => 'Portfolio design placeholder'], ['category' => 'Digital Design', 'description' => 'Replace this placeholder with a published design from the admin dashboard.', 'status' => 'draft']);
    }
}
