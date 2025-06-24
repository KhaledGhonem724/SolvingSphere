import { Head, Link, useForm, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

export default function RoadmapShow({ roadmap }: { roadmap: any }) {
  const { delete: destroy } = useForm();
  const user = usePage().props.auth?.user;

  const handleDeleteTopic = (topicId: number) => {
    if (confirm('Are you sure you want to remove this topic from the roadmap?')) {
      destroy(route('roadmaps.remove_topic', {
        roadmap: roadmap.id,
        topicId,
      }), {
        preserveScroll: true
      });
    }
  };

  const handleDeleteRoadmap = () => {
    if (confirm('Are you sure you want to delete this entire roadmap?')) {
      destroy(route('roadmaps.destroy', roadmap.id));
    }
  };

  const isOwner = user?.user_handle === roadmap.owner_id;

  return (
    <AppLayout
      breadcrumbs={[
        { title: 'Roadmaps', href: '/roadmaps' },
        { title: roadmap.title },
      ]}
    >
      <Head title={roadmap.title} />

      <div className="p-4 space-y-6">
        <div className="flex items-center justify-between">
          <h1 className="text-2xl font-bold">{roadmap.title}</h1>
          {isOwner && (
            <div className="flex gap-2">
              <Link href={route('roadmaps.add_topic_view', { roadmap: roadmap.id })}>
                <Button>Edit Topics</Button>
              </Link>
              <Button variant="destructive" onClick={handleDeleteRoadmap}>
                Delete Roadmap
              </Button>
            </div>
          )}
        </div>

        <p className="text-gray-600">{roadmap.description}</p>

        {roadmap.visibility === 'private' && roadmap.share_token && (
          <div className="text-sm text-gray-500">
            Private Link:{' '}
            <span className="break-all">
              {`${window.location.origin}/roadmaps/shared/${roadmap.share_token}`}
            </span>
          </div>
        )}

        <Card>
          <CardHeader>
            <CardTitle>Topics in this Roadmap</CardTitle>
          </CardHeader>
          <CardContent>
            {roadmap.topics.length > 0 ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {roadmap.topics.map((topic: any) => (
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

                      {isOwner && (
                        <Button
                          variant="destructive"
                          size="sm"
                          className="mt-2"
                          onClick={() => handleDeleteTopic(topic.id)}
                        >
                          Remove from Roadmap
                        </Button>
                      )}
                    </CardContent>
                  </Card>
                ))}
              </div>
            ) : (
              <p className="text-gray-500">No topics added yet.</p>
            )}
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
