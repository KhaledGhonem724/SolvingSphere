import { Head, useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface PageProps {
  sheet: {
    id: number;
    title: string;
  };
}

export default function AddProblem() {
  const { sheet } = usePage<PageProps>().props;

  const { data, setData, post, processing, errors } = useForm({
    sheet_id: sheet.id,
    problem_handles: [''],
  });

  const handleChange = (index: number, value: string) => {
    const updated = [...data.problem_handles];
    updated[index] = value;
    setData('problem_handles', updated);
  };

  const addField = () => {
    setData('problem_handles', [...data.problem_handles, '']);
  };

  const removeField = (index: number) => {
    const updated = [...data.problem_handles];
    updated.splice(index, 1);
    setData('problem_handles', updated);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('sheet.add_problem'));
  };

  return (
    <AppLayout breadcrumbs={[{ title: 'Add Problems', href: `/sheet/${sheet.id}/add-problem` }]}>
      <Head title="Add Problems to Sheet" />

      <form onSubmit={handleSubmit} className="p-4 space-y-4">
        <Card>
          <CardHeader>
            <CardTitle>Add Problems to "{sheet.title}"</CardTitle>
          </CardHeader>

          <CardContent className="space-y-3">
            {data.problem_handles.map((handle, index) => (
              <div key={index} className="flex gap-2 items-center">
                <Input
                  value={handle}
                  onChange={(e) => handleChange(index, e.target.value)}
                  placeholder="Enter problem_handle"
                  className="w-full"
                />
                {data.problem_handles.length > 1 && (
                  <Button
                    type="button"
                    onClick={() => removeField(index)}
                    variant="destructive"
                  >
                    Remove
                  </Button>
                )}
              </div>
            ))}

            <Button type="button" onClick={addField} variant="secondary">
              Add Another Handle
            </Button>

            {errors.problem_handles && (
              <p className="text-red-500">{errors.problem_handles}</p>
            )}

            <Button type="submit" disabled={processing}>
              Submit
            </Button>
          </CardContent>
        </Card>
      </form>
    </AppLayout>
  );
}
