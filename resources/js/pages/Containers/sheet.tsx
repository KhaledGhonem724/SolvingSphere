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
  owner_id: string;
};

type IndexProps = {
  sheets: Sheet[];
  authHandle: string;
};

export default function SheetPage({ sheets, authHandle }: IndexProps) {
  const [search, setSearch] = useState('');
  const [onlyMine, setOnlyMine] = useState(false);

  const filteredSheets = sheets.filter(sheet => {
    const matchesSearch = sheet.title.toLowerCase().includes(search.toLowerCase());
    const matchesOwner = onlyMine ? sheet.owner_id === authHandle : true;
    return matchesSearch && matchesOwner;
  });

  return (
    <AppLayout breadcrumbs={[{ title: 'Sheet', href: '/sheet' }]}>  
      <Head title="All Sheets" />
      <div className="p-4 space-y-4">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <Input
            type="text"
            placeholder="Search sheets..."
            value={search}
            onChange={e => setSearch(e.target.value)}
            className="w-full max-w-md"
          />

          <div className="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 w-full sm:w-auto">
            <div className="flex items-center gap-2 text-sm">
              <span>Show only my sheets</span>
              <button
                onClick={() => setOnlyMine(!onlyMine)}
                className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 ${
                  onlyMine ? 'bg-green-500' : 'bg-gray-300'
                }`}
              >
                <span
                  className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ${
                    onlyMine ? 'translate-x-6' : 'translate-x-1'
                  }`}
                />
              </button>
            </div>

            <Link href={route('sheet.create')} className="w-full sm:w-auto">
              <Button className="w-full sm:w-auto">Create Sheet</Button>
            </Link>
          </div>
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
