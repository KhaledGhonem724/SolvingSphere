import { Head, Link, useForm, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

export default function TopicShow({ topic }: { topic: any }) {
  const { delete: destroy } = useForm();
  const user = usePage().props.auth?.user;

  const handleDeleteProblem = (problemHandle: string) => {
  if (confirm('Are you sure you want to remove this problem from the topic?')) {
    destroy(route('topics.remove_problem', {
      topic: topic.id,
      problemHandle,
    }), {
      preserveScroll: true
    });
  }
};


  const handleDeleteTopic = () => {
    if (confirm('Are you sure you want to delete this entire topic?')) {
      destroy(route('topics.destroy', topic.id));
    }
  };

  const isOwner = user?.user_handle === topic.owner_id;

  return (
    <AppLayout
      breadcrumbs={[
        { title: 'Topics', href: '/topics' },
        { title: topic.title },
      ]}
    >
      <Head title={topic.title} />

      <div className="p-4 space-y-6">
        <div className="flex items-center justify-between">
          <h1 className="text-2xl font-bold">{topic.title}</h1>
          {isOwner && (
            <div className="flex gap-2">
              <Link href={route('topics.add_problem_view', { topic: topic.id })}>
                <Button>Edit Problems</Button>
              </Link>
              <Button variant="destructive" onClick={handleDeleteTopic}>
                Delete Topic
              </Button>
            </div>
          )}
        </div>

        <p className="text-gray-600">{topic.description}</p>

        {topic.visibility === 'private' && topic.share_token && (
          <div className="text-sm text-gray-500">
            Private Link:{' '}
            <span className="break-all">
              {`${window.location.origin}/topics/shared/${topic.share_token}`}
            </span>
          </div>
        )}

        <Card>
          <CardHeader>
            <CardTitle>Problems</CardTitle>
          </CardHeader>
          <CardContent>
            {topic.problems.length > 0 ? (
              <ul className="space-y-2">
                {topic.problems.map((problem: any, index: number) => (
                  <li
                    key={index}
                    className="border p-3 rounded : transition space-y-1"
                  >
                    <div className="flex justify-between items-center">
                      <Link
                        href={route('problems.show', problem.problem_handle)}
                        className="text-blue-600 hover:underline"
                      >
                        {problem.title}
                      </Link>

                      {isOwner && (
                        <Button
                          variant="destructive"
                          size="sm"
                          onClick={() => handleDeleteProblem(problem.problem_handle)}
                        >
                          Remove
                        </Button>
                      )}
                    </div>

                    {problem.pivot?.external_link && (
                      <a
                        href={problem.pivot.external_link}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-sm text-gray-500 hover:underline break-all block"
                      >
                        {problem.pivot.external_link}
                      </a>
                    )}
                  </li>
                ))}
              </ul>
            ) : (
              <p className="text-gray-500">No problems added yet.</p>
            )}
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
