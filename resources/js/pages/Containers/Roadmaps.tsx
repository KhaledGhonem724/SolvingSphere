import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

type Roadmap = {
  id: number;
  title: string;
  description: string;
  visibility: 'public' | 'private';
  owner_id: string;
  share_token?: string | null;
  topics_count: number;
};

type PageProps = {
  roadmaps: Roadmap[];
  authHandle: string;
};

export default function RoadmapsPage({ roadmaps, authHandle }: PageProps) {
  const [search, setSearch] = useState('');
  const [onlyMine, setOnlyMine] = useState(false);

  const filteredRoadmaps = roadmaps.filter(roadmap => {
    const matchesSearch = roadmap.title.toLowerCase().includes(search.toLowerCase());
    const matchesOwner = onlyMine ? roadmap.owner_id === authHandle : true;
    return matchesSearch && matchesOwner;
  });

  return (
    <AppLayout breadcrumbs={[{ title: 'Roadmaps', href: '/roadmaps' }]}>
      <Head title="All Roadmaps" />

      <div className="p-4 space-y-4">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <Input
            type="text"
            placeholder="Search roadmaps..."
            value={search}
            onChange={e => setSearch(e.target.value)}
            className="w-full max-w-md"
          />

          <div className="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 w-full sm:w-auto">
            <div className="flex items-center gap-2 text-sm">
              <span>Show only my roadmaps</span>
              <button
                onClick={() => setOnlyMine(!onlyMine)}
                className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 ${
                  onlyMine ? 'bg-green-500' : 'bg-gray-300'
                }`}
              >
                <span
                  className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ${
                    onlyMine ? 'translate-x-6' : 'translate-x-1'
                  }`}
                />
              </button>
            </div>

            <Link href={route('roadmaps.create')} className="w-full sm:w-auto">
              <Button className="w-full sm:w-auto">Create Roadmap</Button>
            </Link>
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          {filteredRoadmaps.map(roadmap => (
            <Card key={roadmap.id}>
              <CardHeader>
                <CardTitle>{roadmap.title}</CardTitle>
              </CardHeader>
              <CardContent>
                <p>{roadmap.topics_count} topics</p>
                <p className="text-sm text-gray-600">{roadmap.description}</p>

                {roadmap.visibility === 'public' ? (
                  <>
                    <Link
                      href={route('roadmaps.show', roadmap.id)}
                      className="text-blue-500 hover:underline mt-2 inline-block"
                    >
                      View Roadmap
                    </Link>
                    <div className="text-sm text-gray-500 mt-1">
                      Link:{' '}
                      <span className="break-all">
                        {`${window.location.origin}/roadmaps/${roadmap.id}`}
                      </span>
                    </div>
                  </>
                ) : roadmap.share_token ? (
                  <>
                    <Link
                      href={route('roadmaps.shared', { token: roadmap.share_token })}
                      className="text-blue-500 hover:underline mt-2 inline-block"
                    >
                      View Roadmap
                    </Link>
                    <div className="text-sm text-gray-500 mt-1">
                      Private Link:{' '}
                      <span className="break-all">
                        {`${window.location.origin}/roadmaps/shared/${roadmap.share_token}`}
                      </span>
                    </div>
                  </>
                ) : (
                  <div className="text-sm text-red-500 mt-2">
                    Private link not available
                  </div>
                )}
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </AppLayout>
  );
}
