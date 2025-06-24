// File: resources/js/pages/Containers/Topics.tsx

import { Head, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

type Topic = {
  id: number;
  title: string;
  description: string;
  visibility: 'public' | 'private';
  share_token?: string | null;
  owner_id: string;
  problems_count: number;
};

type PageProps = {
  topics: Topic[];
  authHandle: string;
};

export default function TopicsPage({ topics, authHandle }: PageProps) {
  const [search, setSearch] = useState('');
  const [onlyMine, setOnlyMine] = useState(false);

  const filteredTopics = topics.filter(topic => {
    const matchesSearch = topic.title.toLowerCase().includes(search.toLowerCase());
    const matchesOwner = onlyMine ? topic.owner_id === authHandle : true;
    return matchesSearch && matchesOwner;
  });

  return (
    <AppLayout breadcrumbs={[{ title: 'Topics', href: '/topics' }]}>
      <Head title="All Topics" />

      <div className="p-4 space-y-4">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <Input
            type="text"
            placeholder="Search topics..."
            value={search}
            onChange={e => setSearch(e.target.value)}
            className="w-full max-w-md"
          />

          <div className="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 w-full sm:w-auto">
            <div className="flex items-center gap-2 text-sm">
              <span>Show only my topics</span>
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

            <Link href={route('topics.create')} className="w-full sm:w-auto">
              <Button className="w-full sm:w-auto">Create Topic</Button>
            </Link>
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          {filteredTopics.map(topic => (
            <Card key={topic.id}>
              <CardHeader>
                <CardTitle>{topic.title}</CardTitle>
              </CardHeader>
              <CardContent>
                <p>{topic.problems_count} problems</p>
                <p className="text-sm text-gray-600">{topic.description}</p>

                {topic.visibility === 'public' ? (
  <>
    <Link
      href={route('topics.show', topic.id)}
      className="text-blue-500 hover:underline mt-2 inline-block"
    >
      View Topic
    </Link>
    <div className="text-sm text-gray-500 mt-1">
      Link:{' '}
      <span className="break-all">
        {`${window.location.origin}/topics/${topic.id}`}
      </span>
    </div>
  </>
) : topic.share_token ? (
  <>
    <Link
      href={route('topics.shared', { token: topic.share_token })}
      className="text-blue-500 hover:underline mt-2 inline-block"
    >
      View Topic
    </Link>
    <div className="text-sm text-gray-500 mt-1">
      Private Link:{' '}
      <span className="break-all">
        {`${window.location.origin}/topics/shared/${topic.share_token}`}
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
