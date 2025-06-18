// File: resources/js/pages/Containers/create_sheet.tsx
import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

export default function CreateSheet() {
  const { data, setData, post, processing, errors } = useForm({
    title: '',
    description: '',
    visibility: 'public',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('sheet.store'));
  };

  return (
    <AppLayout breadcrumbs={[{ title: 'Create Sheet', href: '/sheet/create' }]}> 
      <Head title="Create Sheet" />
      <form onSubmit={handleSubmit} className="p-4 space-y-4">
        <Card>
          <CardHeader>
            <CardTitle>Create New Sheet</CardTitle>
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
              className="border rounded p-2 w-full"
            >
              <option value="public">Public</option>
              <option value="private">Private</option>
            </select>

            <Button type="submit" disabled={processing} className="mt-4">
              Create Sheet
            </Button>
          </CardContent>
        </Card>
      </form>
    </AppLayout>
  );
}
