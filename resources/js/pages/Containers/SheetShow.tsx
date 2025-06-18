// resources/js/pages/Containers/SheetShow.tsx
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

export default function SheetShow({ sheet }: { sheet: any }) {
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
          <Link href={`/sheet/${sheet.id}/edit-problems`}>
            <Button>Edit Problems</Button>
          </Link>
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
                    className="border p-2 rounded hover:bg-gray-50 transition"
                  >
                    <a
                      href={`https://codeforces.com/problemset/problem/${problem.contest_id}/${problem.index}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-blue-600 hover:underline"
                    >
                      {problem.name || problem.handle}
                    </a>
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
