import { Head, Link, useForm, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

export default function SheetShow({ sheet }: { sheet: any }) {
  const { delete: destroy } = useForm();
  const user = usePage().props.auth?.user;

  const handleDelete = (problemId: number) => {
    if (confirm('Are you sure you want to remove this problem from the sheet?')) {
      destroy(route('sheet.remove_problem', { sheet: sheet.id, problem: problemId }), {
        preserveScroll: true,
      });
    }
  };

  const isOwner = user?.user_handle === sheet.owner_id;

  return (
    <AppLayout
      breadcrumbs={[
        { title: 'Sheets', href: '/sheet' },
        { title: sheet.title },
      ]}
    >
      <Head title={sheet.title} />

      <div className="p-4 space-y-6">
        <div className="flex items-center justify-between">
          <h1 className="text-2xl font-bold">{sheet.title}</h1>
          {isOwner && (
            <Link href={route('sheet.add_problem_view', { sheet: sheet.id })}>
              <Button>Edit Problems</Button>
            </Link>
          )}
        </div>

        <p className="text-gray-600">{sheet.description}</p>

        <Card>
          <CardHeader>
            <CardTitle>Problems</CardTitle>
          </CardHeader>
          <CardContent>
            {sheet.problems.length > 0 ? (
              <ul className="space-y-2">
                {sheet.problems.map((problem: any, index: number) => (
                  <li
                    key={index}
                    className="border p-2 rounded flex justify-between items-center hover:bg-gray-50 transition"
                  >
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
                        onClick={() => handleDelete(problem.id)}
                      >
                        Remove
                      </Button>
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
