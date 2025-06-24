// File: resources/js/pages/Containers/CreateTopic.tsx
import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

export default function CreateTopic() {
  const { data, setData, post, processing, errors } = useForm({
    title: '',
    description: '',
    visibility: 'public',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('topics.store'));
  };

  return (
    <AppLayout breadcrumbs={[{ title: 'Create Topic', href: '/topic/create' }]}>
      <Head title="Create Topic" />
      <form onSubmit={handleSubmit} className="p-4 space-y-4">
        <Card>
          <CardHeader>
            <CardTitle>Create New Topic</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <Input
              placeholder="Title"
              value={data.title}
              onChange={e => setData('title', e.target.value)}
            />
            <Input
              placeholder="Description"
              value={data.description}
              onChange={e => setData('description', e.target.value)}
            />
            <select
              value={data.visibility}
              onChange={e => setData('visibility', e.target.value)}
              className="w-full rounded-md border border-black bg-white text-black dark:bg-black dark:text-white dark:border-white p-2 focus:outline-none"
            >
              <option value="public">Public</option>
              <option value="private">Private</option>
            </select>

            <Button type="submit" disabled={processing} className="mt-4">
              Create Topic
            </Button>
          </CardContent>
        </Card>
      </form>
    </AppLayout>
  );
}
