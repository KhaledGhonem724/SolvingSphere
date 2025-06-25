import { Head, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type User } from '@/types';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { BookOpen, Rocket, Lightbulb, ListChecks, Users, Trophy, Compass } from 'lucide-react';
import React from 'react';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Home',
    href: '/dashboard',
  },
];

// Animation keyframes (inject into the page)
const animationStyles = `
@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(60px); }
  100% { opacity: 1; transform: translateY(0); }
}
.fade-in-up {
  animation: fadeInUp 1s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.fade-in-up-delay-1 {
  animation: fadeInUp 1s 0.15s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.fade-in-up-delay-2 {
  animation: fadeInUp 1s 0.3s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.fade-in-up-delay-3 {
  animation: fadeInUp 1s 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.fade-in-up-delay-4 {
  animation: fadeInUp 1s 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.fade-in-up-delay-5 {
  animation: fadeInUp 1s 0.75s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.fade-in-up-delay-6 {
  animation: fadeInUp 1s 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
}
@keyframes rocketFloat {
  0%, 100% { transform: translateY(0) rotate(12deg); }
  50% { transform: translateY(-16px) rotate(12deg); }
}
.rocket-float {
  animation: rocketFloat 2.5s ease-in-out infinite;
}
@keyframes bubbleFloat {
  0% { opacity: 0.2; transform: translateY(0) scale(1); }
  50% { opacity: 0.5; }
  100% { opacity: 0; transform: translateY(-120px) scale(1.2); }
}
.hero-bubble {
  position: absolute;
  border-radius: 9999px;
  opacity: 0.3;
  animation: bubbleFloat 6s linear infinite;
}
.hero-bubble-1 { left: 10%; bottom: 10%; width: 60px; height: 60px; background: #fff; animation-delay: 0s; }
.hero-bubble-2 { left: 30%; bottom: 5%; width: 40px; height: 40px; background: #fff; animation-delay: 1.5s; }
.hero-bubble-3 { left: 60%; bottom: 12%; width: 80px; height: 80px; background: #fff; animation-delay: 3s; }
.hero-bubble-4 { left: 80%; bottom: 8%; width: 50px; height: 50px; background: #fff; animation-delay: 2s; }
.hero-bubble-5 { left: 50%; bottom: 0%; width: 30px; height: 30px; background: #fff; animation-delay: 4s; }

/* Hide scrollbars for home page and sidebar */
.hide-scrollbar {
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* IE 10+ */
}
.hide-scrollbar::-webkit-scrollbar {
  display: none; /* Chrome/Safari/Webkit */
}
`;

export default function Dashboard() {
  const { props } = usePage();
  const user = (props.auth?.user || {}) as Partial<User>;

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Home" />
      {/* Inject animation styles */}
      <style>{animationStyles}</style>
      <div className="flex flex-1 flex-col items-center min-h-[60vh] p-4 gap-10 hide-scrollbar" style={{ overflow: 'auto' }}>
        {/* Hero Section */}
        <div className="relative w-full max-w-4xl flex flex-col items-center justify-center text-center py-24 px-6 bg-gradient-to-br from-blue-700 via-blue-500 to-blue-400 rounded-3xl shadow-2xl overflow-hidden mb-2 fade-in-up">
          {/* Animated SVG bubbles background */}
          <div className="pointer-events-none absolute inset-0 z-0">
            <div className="hero-bubble hero-bubble-1" />
            <div className="hero-bubble hero-bubble-2" />
            <div className="hero-bubble hero-bubble-3" />
            <div className="hero-bubble hero-bubble-4" />
            <div className="hero-bubble hero-bubble-5" />
          </div>
          <Rocket className="absolute top-6 right-8 md:right-16 text-white/30 w-24 h-24 md:w-32 md:h-32 rocket-float z-10" />
          <h1 className="text-5xl md:text-6xl font-extrabold mb-6 text-white drop-shadow-lg z-10">
            Welcome{user.name ? `, ${user.name}` : ''}!
          </h1>
          <p className="text-2xl md:text-3xl font-medium text-white/90 mb-4 max-w-2xl z-10">
            Your journey to mastering problem solving starts here.
          </p>
          <p className="text-lg md:text-xl text-white/80 max-w-2xl z-10">
            Use the sidebar to explore problems, organize your learning, and connect with the SolvingSphere community.
          </p>
        </div>

        {/* Info Sections */}
        <div className="w-full max-w-6xl grid grid-cols-1 md:grid-cols-3 gap-6">
          {/* About Card */}
          <Card className="flex flex-col items-center text-center h-full fade-in-up-delay-1">
            <CardHeader>
              <BookOpen className="w-10 h-10 text-blue-600 mx-auto mb-2" />
              <CardTitle>About SolvingSphere</CardTitle>
              <CardDescription>What is this platform?</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-base opacity-90 mb-2">
                SolvingSphere is a collaborative platform for problem solvers, learners, and educators. Practice coding, organize your learning, and connect with others.
              </p>
              <ul className="list-disc pl-5 text-left text-sm opacity-80 space-y-1">
                <li>Practice and solve a wide variety of problems.</li>
                <li>Create and share sheets, topics, and roadmaps.</li>
                <li>Join groups, participate in discussions, and write blogs.</li>
                <li>Track your progress and celebrate your achievements.</li>
              </ul>
            </CardContent>
          </Card>

          {/* Getting Started Card */}
          <Card className="flex flex-col items-center text-center h-full fade-in-up-delay-2">
            <CardHeader>
              <ListChecks className="w-10 h-10 text-green-600 mx-auto mb-2" />
              <CardTitle>How to Get Started</CardTitle>
              <CardDescription>Begin your journey</CardDescription>
            </CardHeader>
            <CardContent>
              <ol className="list-decimal pl-5 text-left text-sm space-y-1">
                <li>Explore the <span className="font-semibold text-blue-600">Problems</span> section.</li>
                <li>Create <span className="font-semibold text-blue-600">Sheets</span> and <span className="font-semibold text-blue-600">Topics</span>.</li>
                <li>Join or create <span className="font-semibold text-blue-600">Groups</span>.</li>
                <li>Read and write <span className="font-semibold text-blue-600">Blogs</span>.</li>
                <li>Track your progress on your <span className="font-semibold text-blue-600">Profile</span>.</li>
              </ol>
            </CardContent>
          </Card>

          {/* Tips Card */}
          <Card className="flex flex-col items-center text-center h-full fade-in-up-delay-3">
            <CardHeader>
              <Lightbulb className="w-10 h-10 text-yellow-500 mx-auto mb-2" />
              <CardTitle>Tips & Best Practices</CardTitle>
              <CardDescription>Make the most of SolvingSphere</CardDescription>
            </CardHeader>
            <CardContent>
              <ul className="list-disc pl-5 text-left text-sm opacity-90 space-y-1">
                <li>Stay consistent—solve at least one problem daily.</li>
                <li>Use sheets and topics to break down complex subjects.</li>
                <li>Engage with the community in groups and blogs.</li>
                <li>Share your solutions to help others learn.</li>
                <li>Keep your profile updated to showcase achievements.</li>
              </ul>
            </CardContent>
          </Card>

          {/* Community & Collaboration Card */}
          <Card className="flex flex-col items-center text-center h-full fade-in-up-delay-4">
            <CardHeader>
              <Users className="w-10 h-10 text-indigo-600 mx-auto mb-2" />
              <CardTitle>Community & Collaboration</CardTitle>
              <CardDescription>Connect and grow together</CardDescription>
            </CardHeader>
            <CardContent>
              <ul className="list-disc pl-5 text-left text-sm opacity-90 space-y-1">
                <li>Join groups to collaborate on problem sets and topics.</li>
                <li>Participate in discussions and share your insights.</li>
                <li>Help others by answering questions and reviewing solutions.</li>
                <li>Build your network with like-minded learners and mentors.</li>
              </ul>
            </CardContent>
          </Card>

          {/* Achievements & Progress Card */}
          <Card className="flex flex-col items-center text-center h-full fade-in-up-delay-5">
            <CardHeader>
              <Trophy className="w-10 h-10 text-amber-500 mx-auto mb-2" />
              <CardTitle>Achievements & Progress</CardTitle>
              <CardDescription>Celebrate your milestones</CardDescription>
            </CardHeader>
            <CardContent>
              <ul className="list-disc pl-5 text-left text-sm opacity-90 space-y-1">
                <li>Track your solved problems, streaks, and badges.</li>
                <li>Unlock achievements as you progress.</li>
                <li>View your learning history and celebrate your growth.</li>
                <li>Share your accomplishments with the community.</li>
              </ul>
            </CardContent>
          </Card>

          {/* Explore & Discover Card */}
          <Card className="flex flex-col items-center text-center h-full fade-in-up-delay-6">
            <CardHeader>
              <Compass className="w-10 h-10 text-pink-500 mx-auto mb-2" />
              <CardTitle>Explore & Discover</CardTitle>
              <CardDescription>Find new opportunities</CardDescription>
            </CardHeader>
            <CardContent>
              <ul className="list-disc pl-5 text-left text-sm opacity-90 space-y-1">
                <li>Browse trending problems and topics curated for you.</li>
                <li>Discover new sheets, roadmaps, and learning paths.</li>
                <li>Follow top contributors and learn from their journeys.</li>
                <li>Stay updated with the latest platform features and events.</li>
              </ul>
            </CardContent>
          </Card>
        </div>
      </div>
    </AppLayout>
  );
}
