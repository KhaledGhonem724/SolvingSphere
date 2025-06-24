import { Head, useForm, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface PageProps {
  roadmap: {
    id: number;
    title: string;
  };
  topics: {
    id: number;
    title: string;
  }[];
}

export default function AddRoadmapTopics() {
  const { roadmap, topics } = usePage<PageProps>().props;

  const { data, setData, post, processing, errors } = useForm<{
    roadmap_id: number;
    topics: { id: number | '' }[];
  }>({
    roadmap_id: roadmap.id,
    topics: [{ id: '' }],
  });

  const handleChange = (index: number, value: string) => {
    const updated = [...data.topics];
    updated[index] = { id: value === '' ? '' : parseInt(value) };
    setData('topics', updated);
  };

  const addField = () => {
    setData('topics', [...data.topics, { id: '' }]);
  };

  const removeField = (index: number) => {
    const updated = [...data.topics];
    updated.splice(index, 1);
    setData('topics', updated);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    const cleanedTopics = data.topics
      .filter((t) => t.id !== '' && !isNaN(Number(t.id)))
      .map((t) => ({ id: Number(t.id) }));

    setData('topics', cleanedTopics);

    post(route('roadmaps.add_topic'), {
      preserveScroll: true,
    });
  };

  const selectedIds = data.topics.map((t) => t.id);

  return (
    <AppLayout breadcrumbs={[{ title: 'Add Topics', href: `/roadmaps/${roadmap.id}/add-topic` }]}>
      <Head title={`Add Topics to ${roadmap.title}`} />

      <form onSubmit={handleSubmit} className="p-4 space-y-4">
        <Card>
          <CardHeader>
            <CardTitle>Add Topics to "{roadmap.title}"</CardTitle>
          </CardHeader>

          <CardContent className="space-y-4">
            {data.topics.map((item, index) => (
              <div key={index} className="grid md:grid-cols-[1fr_auto] gap-3 items-start">
                <div className="w-full">
                  <select
                    value={item.id}
                    onChange={(e) => handleChange(index, e.target.value)}
                    className="w-full rounded-md border border-black bg-white text-black dark:bg-black dark:text-white dark:border-white p-2 focus:outline-none"
                  >
                    <option value="">Select Topic</option>
                    {topics.map((topic) => (
                      <option
                        key={topic.id}
                        value={topic.id}
                        disabled={selectedIds.includes(topic.id) && topic.id !== item.id}
                      >
                        {topic.title}
                      </option>
                    ))}
                  </select>

                  {errors[`topics.${index}.id`] && (
                    <p className="text-sm text-red-500 mt-1">
                      {errors[`topics.${index}.id`]}
                    </p>
                  )}
                </div>

                {data.topics.length > 1 && (
                  <Button
                    type="button"
                    onClick={() => removeField(index)}
                    className="h-full bg-black text-white hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200"
                  >
                    Remove
                  </Button>
                )}
              </div>
            ))}

            <div className="flex flex-wrap gap-2">
              <Button
                type="button"
                onClick={addField}
                className="bg-black text-white hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200"
              >
                Add Another Topic
              </Button>

              <Button
                type="submit"
                disabled={processing}
                className="bg-black text-white hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200"
              >
                Submit
              </Button>
            </div>

            {errors.topics && typeof errors.topics === 'string' && (
              <p className="text-sm text-red-500">{errors.topics}</p>
            )}
          </CardContent>
        </Card>
      </form>
    </AppLayout>
  );
}
