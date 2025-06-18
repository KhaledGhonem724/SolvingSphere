// File: resources/js/pages/Containers/sheet.tsx
import { Head, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

// ---------- List Page ----------
type Sheet = {
  id: number;
  title: string;
  problems_count: number;
  visibility: 'public' | 'private';
  share_token?: string | null;
};

type IndexProps = {
  sheets: Sheet[];
};

export default function SheetPage({ sheets }: IndexProps) {
  const [search, setSearch] = useState('');
  const filteredSheets = sheets.filter(sheet =>
    sheet.title.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <AppLayout breadcrumbs={[{ title: 'Sheet', href: '/sheet' }]}>  
      <Head title="All Sheets" />
      <div className="p-4 space-y-4">
        <div className="flex justify-between items-center gap-4">
          <Input
            type="text"
            placeholder="Search sheets..."
            value={search}
            onChange={e => setSearch(e.target.value)}
            className="w-full max-w-md"
          />
          <Link href={route('sheet.create')}>
            <Button>Create Sheet</Button>
          </Link>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          {filteredSheets.map(sheet => (
            <Card key={sheet.id}>
              <CardHeader>
                <CardTitle>{sheet.title}</CardTitle>
              </CardHeader>
              <CardContent>
                <p>{sheet.problems_count} problems</p>
                {sheet.visibility === 'public' ? (
                  <>
                    <Link
                      href={route('sheet.show', sheet.id)}
                      className="text-blue-500 hover:underline mt-2 inline-block"
                    >
                      View Sheet
                    </Link>
                    <div className="text-sm text-gray-500 mt-1">
                      Link:{' '}
                      <span className="break-all">
                        {`${window.location.origin}/sheet/${sheet.id}`}
                      </span>
                    </div>
                  </>
                ) : (
                  <>
                    <Link
                      href={route('sheet.shared', { token: sheet.share_token })}
                      className="text-blue-500 hover:underline mt-2 inline-block"
                    >
                      View Sheet
                    </Link>
                    <div className="text-sm text-gray-500 mt-1">
                      Private Link:{' '}
                      <span className="break-all">
                        {`${window.location.origin}/sheet/shared/${sheet.share_token}`}
                      </span>
                    </div>
                  </>
                )}
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </AppLayout>
  );
}
