// resources/js/pages/Containers/EditProblems.tsx
import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { useState } from 'react';

export default function EditProblems({ sheet }: { sheet: any }) {
  const [handles, setHandles] = useState(sheet.problems.map((p: any) => p.handle) || ['']);
  const { post, processing } = useForm({});

  const addHandle = () => setHandles([...handles, '']);
  const removeHandle = (index: number) => {
    if (handles.length === 1) return;
    const newHandles = [...handles];
    newHandles.splice(index, 1);
    setHandles(newHandles);
  };

  const updateHandle = (index: number, value: string) => {
    const newHandles = [...handles];
    newHandles[index] = value;
    setHandles(newHandles);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(`/sheet/${sheet.id}/problems`, {
      problem_handles: handles,
      preserveScroll: true,
      onSuccess: () => {
        window.location.href = `/sheet/${sheet.id}`;
      },
    });
  };

  return (
    <AppLayout
      breadcrumbs={[
        { title: 'Sheets', href: '/sheet' },
        { title: sheet.title, href: `/sheet/${sheet.id}` },
        { title: 'Edit Problems' },
      ]}
    >
      <Head title={`Edit Problems - ${sheet.title}`} />

      <form onSubmit={handleSubmit} className="p-4 space-y-4">
        <Card>
          <CardHeader>
            <CardTitle>Edit Problems for: {sheet.title}</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            {handles.map((handle, index) => (
              <div key={index} className="flex gap-2 items-center">
                <Input
                  placeholder={`Problem handle #${index + 1}`}
                  value={handle}
                  onChange={(e) => updateHandle(index, e.target.value)}
                  className="w-full"
                />
                <Button
                  type="button"
                  variant="destructive"
                  onClick={() => removeHandle(index)}
                  disabled={handles.length === 1}
                >
                  ×
                </Button>
              </div>
            ))}

            <Button type="button" onClick={addHandle}>
              + Add Another
            </Button>

            <Button type="submit" disabled={processing} className="mt-4">
              Save Problems
            </Button>
          </CardContent>
        </Card>
      </form>
    </AppLayout>
  );
}
