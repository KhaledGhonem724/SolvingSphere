import { Head, useForm, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface PageProps {
  topic: {
    id: number;
    title: string;
  };
  problems: {
    problem_handle: string;
  }[];
}

export default function AddTopicProblem() {
  const { topic } = usePage<PageProps>().props;

  const { data, setData, post, processing, errors } = useForm({
    topic_id: topic.id,
    problems: [{ handle: '', external_link: '' }],
  });

  const handleChange = (index: number, field: string, value: string) => {
    const updated = [...data.problems];
    updated[index][field] = value;
    setData('problems', updated);
  };

  const addField = () => {
    setData('problems', [...data.problems, { handle: '', external_link: '' }]);
  };

  const removeField = (index: number) => {
    const updated = [...data.problems];
    updated.splice(index, 1);
    setData('problems', updated);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('topics.add_problem'));
  };

  return (
    <AppLayout breadcrumbs={[{ title: 'Add Problems', href: `/topics/${topic.id}/add-problem` }]}>
      <Head title={`Add Problems to ${topic.title}`} />

      <form onSubmit={handleSubmit} className="p-4 space-y-4">
        <Card>
          <CardHeader>
            <CardTitle>Add Problems to "{topic.title}"</CardTitle>
          </CardHeader>

          <CardContent className="space-y-4">
            {data.problems.map((item, index) => (
              <div key={index} className="grid md:grid-cols-2 gap-2 items-center">
                <Input
                  value={item.handle}
                  onChange={(e) => handleChange(index, 'handle', e.target.value)}
                  placeholder="Enter problem_handle"
                  className="w-full"
                />
                <Input
                  value={item.external_link}
                  onChange={(e) => handleChange(index, 'external_link', e.target.value)}
                  placeholder="Optional external link"
                  className="w-full"
                />
                <div className="md:col-span-2">
                  {data.problems.length > 1 && (
                    <Button type="button" onClick={() => removeField(index)} variant="destructive">
                      Remove
                    </Button>
                  )}
                </div>
              </div>
            ))}

            <Button type="button" onClick={addField} variant="secondary">
              Add Another Problem
            </Button>

            {errors.problems && (
              <p className="text-red-500">{errors.problems}</p>
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
