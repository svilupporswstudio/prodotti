import { useMemo, useState } from 'react';
import JSZip from 'jszip';
import {
  Check,
  Copy,
  Download,
  FileCode2,
  FileText,
  Folder,
  FolderOpen,
  GitBranch,
  Search,
} from 'lucide-react';
import {
  AUDIT_CHANGES,
  PLUGIN_FILES,
  type AuditChange,
  type PluginFileEntry,
} from './data/pluginFiles';

type ActiveSection = 'files' | 'audit' | 'workflow' | 'install';
type SeverityFilter = 'Tutte' | AuditChange['severity'];

const SEVERITY_TEXT_CLASS: Record<AuditChange['severity'], string> = {
  Critico: 'text-red-700 font-semibold',
  Compatibilità: 'text-amber-700 font-semibold',
  Sicurezza: 'text-blue-700 font-semibold',
  Struttura: 'text-emerald-700 font-semibold',
};

export default function App() {
  const [activeSection, setActiveSection] = useState<ActiveSection>('files');
  const [selectedFileId, setSelectedFileId] = useState<string>(PLUGIN_FILES[0].id);
  const [fileQuery, setFileQuery] = useState<string>('');
  const [severityFilter, setSeverityFilter] = useState<SeverityFilter>('Tutte');
  const [copiedId, setCopiedId] = useState<string | null>(null);
  const [downloadingType, setDownloadingType] = useState<'plugin' | 'repo' | null>(null);

  const selectedFile = useMemo<PluginFileEntry>(() => {
    return PLUGIN_FILES.find((file) => file.id === selectedFileId) ?? PLUGIN_FILES[0];
  }, [selectedFileId]);

  const filteredFiles = useMemo(() => {
    const query = fileQuery.trim().toLowerCase();
    if (!query) {
      return PLUGIN_FILES;
    }
    return PLUGIN_FILES.filter(
      (file) =>
        file.path.toLowerCase().includes(query) ||
        file.summary.toLowerCase().includes(query)
    );
  }, [fileQuery]);

  const groupedFolders = useMemo(() => {
    const folders = new Map<string, PluginFileEntry[]>();
    for (const file of filteredFiles) {
      const current = folders.get(file.folder) ?? [];
      current.push(file);
      folders.set(file.folder, current);
    }
    return Array.from(folders.entries());
  }, [filteredFiles]);

  const filteredChanges = useMemo(() => {
    if (severityFilter === 'Tutte') {
      return AUDIT_CHANGES;
    }
    return AUDIT_CHANGES.filter((item) => item.severity === severityFilter);
  }, [severityFilter]);

  const workflowFile = useMemo(() => {
    return PLUGIN_FILES.find((file) => file.id === 'github-workflow') ?? PLUGIN_FILES[0];
  }, []);

  const triggerDownloadBlob = (blob: Blob, filename: string) => {
    const url = URL.createObjectURL(blob);
    const anchor = document.createElement('a');
    anchor.href = url;
    anchor.download = filename;
    document.body.appendChild(anchor);
    anchor.click();
    document.body.removeChild(anchor);
    URL.revokeObjectURL(url);
  };

  const handleDownloadZip = async (includeWorkflow: boolean) => {
    setDownloadingType(includeWorkflow ? 'repo' : 'plugin');
    try {
      const zip = new JSZip();
      for (const file of PLUGIN_FILES) {
        if (!includeWorkflow && !file.inPluginZip) {
          continue;
        }
        zip.file(file.zipPath, file.content);
      }
      zip.file('prodotti/languages/.gitkeep', '');
      zip.file('prodotti/assets/img/.gitkeep', '');

      const blob = await zip.generateAsync({ type: 'blob' });
      triggerDownloadBlob(
        blob,
        includeWorkflow ? 'prodotti-repo-2.0.0.zip' : 'prodotti-2.0.0.zip'
      );
    } finally {
      setDownloadingType(null);
    }
  };

  const handleCopyContent = async (file: PluginFileEntry) => {
    try {
      await navigator.clipboard.writeText(file.content);
      setCopiedId(file.id);
      window.setTimeout(() => {
        setCopiedId((current) => (current === file.id ? null : current));
      }, 1800);
    } catch {
      const blob = new Blob([file.content], { type: 'text/plain;charset=utf-8' });
      triggerDownloadBlob(blob, file.name);
    }
  };

  const handleDownloadSingleFile = (file: PluginFileEntry) => {
    const blob = new Blob([file.content], { type: 'text/plain;charset=utf-8' });
    triggerDownloadBlob(blob, file.name);
  };

  const codeLines = useMemo(() => selectedFile.content.split('\n'), [selectedFile]);
  const workflowLines = useMemo(() => workflowFile.content.split('\n'), [workflowFile]);

  return (
    <div className="min-h-screen flex flex-col bg-slate-50 text-slate-900">
      <header className="sticky top-0 z-20 bg-white border-b border-slate-200">
        <div className="max-w-[1440px] mx-auto px-6 h-16 flex items-center justify-between gap-8">
          <a
            href="#top"
            onClick={(e) => {
              e.preventDefault();
              setActiveSection('files');
            }}
            className="text-lg font-bold tracking-tight text-slate-900 whitespace-nowrap shrink-0 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#2271B1]"
          >
            Prodotti Packager
          </a>

          <nav className="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
            <button
              type="button"
              onClick={() => setActiveSection('files')}
              className={`py-1 transition-colors whitespace-nowrap shrink-0 cursor-pointer border-b-2 ${
                activeSection === 'files'
                  ? 'text-slate-900 border-[#2271B1]'
                  : 'border-transparent hover:text-slate-900'
              }`}
            >
              Esplora File
            </button>
            <button
              type="button"
              onClick={() => setActiveSection('audit')}
              className={`py-1 transition-colors whitespace-nowrap shrink-0 cursor-pointer border-b-2 ${
                activeSection === 'audit'
                  ? 'text-slate-900 border-[#2271B1]'
                  : 'border-transparent hover:text-slate-900'
              }`}
            >
              Modifiche PHP 8.3
            </button>
            <button
              type="button"
              onClick={() => setActiveSection('workflow')}
              className={`py-1 transition-colors whitespace-nowrap shrink-0 cursor-pointer border-b-2 ${
                activeSection === 'workflow'
                  ? 'text-slate-900 border-[#2271B1]'
                  : 'border-transparent hover:text-slate-900'
              }`}
            >
              Workflow GitHub
            </button>
            <button
              type="button"
              onClick={() => setActiveSection('install')}
              className={`py-1 transition-colors whitespace-nowrap shrink-0 cursor-pointer border-b-2 ${
                activeSection === 'install'
                  ? 'text-slate-900 border-[#2271B1]'
                  : 'border-transparent hover:text-slate-900'
              }`}
            >
              Installazione
            </button>
          </nav>

          <div className="flex items-center shrink-0">
            <button
              type="button"
              onClick={() => handleDownloadZip(false)}
              disabled={downloadingType !== null}
              className="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#2271B1] hover:bg-[#135E96] disabled:opacity-60 rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#2271B1]"
            >
              <Download className="w-4 h-4 shrink-0" />
              <span>{downloadingType === 'plugin' ? 'Preparazione...' : 'Scarica Plugin (.zip)'}</span>
            </button>
          </div>
        </div>
      </header>

      <div className="md:hidden bg-white border-b border-slate-200 px-4 py-2 flex items-center gap-4 overflow-x-auto text-sm font-medium text-slate-600">
        {(['files', 'audit', 'workflow', 'install'] as ActiveSection[]).map((tab) => (
          <button
            key={tab}
            type="button"
            onClick={() => setActiveSection(tab)}
            className={`py-1.5 whitespace-nowrap shrink-0 ${
              activeSection === tab ? 'text-[#2271B1] font-semibold' : ''
            }`}
          >
            {tab === 'files' && 'Esplora File'}
            {tab === 'audit' && 'Modifiche PHP 8.3'}
            {tab === 'workflow' && 'Workflow GitHub'}
            {tab === 'install' && 'Installazione'}
          </button>
        ))}
      </div>

      <section className="bg-white border-b border-slate-200">
        <div className="max-w-[1440px] mx-auto px-6 py-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          <div className="space-y-1.5">
            <h1 className="text-2xl font-bold tracking-tight text-slate-900">
              Plugin WordPress Prodotti · Versione 2.0.0
            </h1>
            <p className="text-sm text-slate-600 max-w-3xl">
              Struttura riorganizzata e codice aggiornato per compatibilità nativa con PHP 8.3 e WordPress 7.0–7.2, completo di workflow GitHub Actions e pacchetto installabile.
            </p>
          </div>

          <dl className="grid grid-cols-2 sm:grid-cols-4 gap-6 lg:gap-8 text-sm shrink-0">
            <div>
              <dt className="text-xs text-slate-500">Minimo WordPress</dt>
              <dd className="font-semibold text-slate-900 tabular-nums mt-0.5">7.0</dd>
            </div>
            <div>
              <dt className="text-xs text-slate-500">Testato fino a</dt>
              <dd className="font-semibold text-slate-900 tabular-nums mt-0.5">7.2 (incl. 7.1)</dd>
            </div>
            <div>
              <dt className="text-xs text-slate-500">Versione PHP</dt>
              <dd className="font-semibold text-slate-900 tabular-nums mt-0.5">8.3+</dd>
            </div>
            <div>
              <dt className="text-xs text-slate-500">File nel pacchetto</dt>
              <dd className="font-semibold text-slate-900 tabular-nums mt-0.5">
                {PLUGIN_FILES.filter((f) => f.inPluginZip).length} file · 3 repo CI
              </dd>
            </div>
          </dl>
        </div>
      </section>

      <main className="flex-1 max-w-[1440px] w-full mx-auto px-6 py-6">
        {activeSection === 'files' && (
          <div className="grid grid-cols-1 lg:grid-cols-[290px_minmax(0,1fr)] gap-6 items-start">
            <aside className="bg-white border border-slate-200 rounded-lg overflow-hidden">
              <div className="p-4 border-b border-slate-200">
                <label htmlFor="file-search" className="sr-only">
                  Cerca file nel plugin
                </label>
                <div className="relative">
                  <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
                  <input
                    id="file-search"
                    type="text"
                    value={fileQuery}
                    onChange={(e) => setFileQuery(e.target.value)}
                    placeholder="Filtra file..."
                    className="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-md text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-[#2271B1]"
                  />
                </div>
              </div>

              <div className="p-3 space-y-4 max-h-[680px] overflow-y-auto">
                {groupedFolders.length === 0 ? (
                  <p className="px-2 py-4 text-sm text-slate-500">
                    Nessun file corrisponde al filtro inserito.
                  </p>
                ) : (
                  groupedFolders.map(([folder, files]) => (
                    <div key={folder} className="space-y-1">
                      <div className="px-2 py-1 flex items-center gap-2 text-xs font-semibold text-slate-500">
                        <FolderOpen className="w-3.5 h-3.5 text-slate-400 shrink-0" />
                        <span className="truncate font-mono">
                          {folder === 'root' ? './' : `${folder}/`}
                        </span>
                      </div>
                      <ul className="space-y-0.5">
                        {files.map((file) => {
                          const isSelected = file.id === selectedFile.id;
                          return (
                            <li key={file.id}>
                              <button
                                type="button"
                                onClick={() => setSelectedFileId(file.id)}
                                className={`w-full text-left px-2.5 py-2 rounded-md text-sm flex items-center justify-between gap-2 transition-colors cursor-pointer ${
                                  isSelected
                                    ? 'bg-slate-900 text-white'
                                    : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900'
                                }`}
                              >
                                <span className="flex items-center gap-2 min-w-0">
                                  {file.language === 'php' ? (
                                    <FileCode2 className="w-4 h-4 shrink-0 opacity-80" />
                                  ) : (
                                    <FileText className="w-4 h-4 shrink-0 opacity-80" />
                                  )}
                                  <span className="truncate font-mono text-xs">{file.name}</span>
                                </span>
                                <span
                                  className={`text-xs font-mono tabular-nums shrink-0 ${
                                    isSelected ? 'text-slate-300' : 'text-slate-400'
                                  }`}
                                >
                                  {file.content.split('\n').length}
                                </span>
                              </button>
                            </li>
                          );
                        })}
                      </ul>
                    </div>
                  ))
                )}

                <div className="pt-2 border-t border-slate-200 px-2 space-y-1 text-xs text-slate-500">
                  <div className="flex items-center gap-2 font-mono">
                    <Folder className="w-3.5 h-3.5 text-slate-400 shrink-0" />
                    <span>prodotti/languages/</span>
                  </div>
                  <div className="flex items-center gap-2 font-mono">
                    <Folder className="w-3.5 h-3.5 text-slate-400 shrink-0" />
                    <span>prodotti/assets/img/</span>
                  </div>
                </div>
              </div>
            </aside>

            <section className="bg-white border border-slate-200 rounded-lg overflow-hidden min-w-0">
              <div className="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div className="min-w-0">
                  <div className="flex items-center gap-2 text-xs text-slate-500">
                    <span className="font-mono truncate">{selectedFile.path}</span>
                    <span aria-hidden="true">·</span>
                    <span className="tabular-nums shrink-0">{codeLines.length} righe</span>
                    <span aria-hidden="true">·</span>
                    <span className="shrink-0">
                      {selectedFile.inPluginZip ? 'Incluso in prodotti.zip' : 'Repository GitHub'}
                    </span>
                  </div>
                  <p className="text-sm font-medium text-slate-900 mt-1">
                    {selectedFile.summary}
                  </p>
                </div>

                <div className="flex items-center gap-2 shrink-0">
                  <button
                    type="button"
                    onClick={() => handleCopyContent(selectedFile)}
                    className="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors whitespace-nowrap shrink-0 cursor-pointer"
                  >
                    {copiedId === selectedFile.id ? (
                      <>
                        <Check className="w-3.5 h-3.5 text-emerald-700" />
                        <span>Copiato</span>
                      </>
                    ) : (
                      <>
                        <Copy className="w-3.5 h-3.5" />
                        <span>Copia codice</span>
                      </>
                    )}
                  </button>
                  <button
                    type="button"
                    onClick={() => handleDownloadSingleFile(selectedFile)}
                    className="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-slate-700 border border-slate-200 hover:bg-slate-50 rounded-md transition-colors whitespace-nowrap shrink-0 cursor-pointer"
                  >
                    <Download className="w-3.5 h-3.5" />
                    <span>Scarica file</span>
                  </button>
                </div>
              </div>

              <div className="bg-slate-950 text-slate-100 overflow-x-auto max-h-[640px] overflow-y-auto">
                <table className="w-full border-collapse text-xs font-mono leading-6">
                  <tbody>
                    {codeLines.map((line, index) => (
                      <tr key={index} className="hover:bg-slate-900/70">
                        <td className="select-none text-right pr-4 pl-4 text-slate-500 tabular-nums w-12 align-top border-r border-slate-800/80">
                          {index + 1}
                        </td>
                        <td className="pl-4 pr-6 whitespace-pre text-slate-100">
                          {line.length > 0 ? line : ' '}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </section>
          </div>
        )}

        {activeSection === 'audit' && (
          <section className="bg-white border border-slate-200 rounded-lg overflow-hidden">
            <div className="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h2 className="text-lg font-semibold text-slate-900">
                  Interventi di compatibilità PHP 8.3 e WordPress 7.0–7.2
                </h2>
                <p className="text-sm text-slate-600 mt-0.5">
                  Riepilogo completo dei bug risolti e degli aggiornamenti applicati ai file sorgenti originali.
                </p>
              </div>

              <div className="flex items-center gap-1 p-1 bg-slate-100 rounded-lg overflow-x-auto shrink-0">
                {(['Tutte', 'Critico', 'Compatibilità', 'Sicurezza', 'Struttura'] as SeverityFilter[]).map(
                  (category) => (
                    <button
                      key={category}
                      type="button"
                      onClick={() => setSeverityFilter(category)}
                      className={`px-3 py-1.5 text-xs font-medium rounded-md transition-colors whitespace-nowrap shrink-0 cursor-pointer ${
                        severityFilter === category
                          ? 'bg-white text-slate-900 shadow-sm'
                          : 'text-slate-600 hover:text-slate-900'
                      }`}
                    >
                      {category}
                    </button>
                  )
                )}
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-sm">
                <thead>
                  <tr className="border-b border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600">
                    <th className="py-3 px-6 w-32">Categoria</th>
                    <th className="py-3 px-4 w-64">Modulo</th>
                    <th className="py-3 px-4">Problema rilevato</th>
                    <th className="py-3 px-6">Soluzione applicata</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-200">
                  {filteredChanges.map((item) => (
                    <tr key={item.id} className="hover:bg-slate-50/70 align-top">
                      <td className={`py-4 px-6 text-xs ${SEVERITY_TEXT_CLASS[item.severity]}`}>
                        {item.severity}
                      </td>
                      <td className="py-4 px-4 font-mono text-xs text-slate-700">
                        {item.file}
                      </td>
                      <td className="py-4 px-4 text-slate-700 leading-relaxed">
                        {item.issue}
                      </td>
                      <td className="py-4 px-6 text-slate-900 leading-relaxed">
                        {item.resolution}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </section>
        )}

        {activeSection === 'workflow' && (
          <section className="space-y-6">
            <div className="bg-white border border-slate-200 rounded-lg p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
              <div className="space-y-1.5">
                <div className="flex items-center gap-2 text-xs text-slate-500">
                  <GitBranch className="w-3.5 h-3.5" />
                  <span className="font-mono">.github/workflows/wordpress-plugin.yml</span>
                  <span aria-hidden="true">·</span>
                  <span>GitHub Actions</span>
                </div>
                <h2 className="text-lg font-semibold text-slate-900">
                  Pipeline CI PHP 8.3 e generazione pacchetto Release
                </h2>
                <p className="text-sm text-slate-600 max-w-3xl">
                  Esegue il controllo sintattico con PHP 8.3 su tutti i file del plugin, verifica la coerenza dei requisiti WordPress 7.0–7.2 e Requires Plugins, genera prodotti.zip escludendo i pattern di .svnignore e supporta il deploy SVN verso WordPress.org.
                </p>
              </div>

              <div className="flex items-center gap-3 shrink-0">
                <button
                  type="button"
                  onClick={() => handleCopyContent(workflowFile)}
                  className="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer"
                >
                  {copiedId === workflowFile.id ? (
                    <>
                      <Check className="w-4 h-4 text-emerald-700" />
                      <span>Copiato</span>
                    </>
                  ) : (
                    <>
                      <Copy className="w-4 h-4" />
                      <span>Copia YAML</span>
                    </>
                  )}
                </button>
                <button
                  type="button"
                  onClick={() => handleDownloadZip(true)}
                  disabled={downloadingType !== null}
                  className="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 disabled:opacity-60 rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer"
                >
                  <Download className="w-4 h-4" />
                  <span>
                    {downloadingType === 'repo'
                      ? 'Preparazione...'
                      : 'Scarica Repo + Workflow (.zip)'}
                  </span>
                </button>
              </div>
            </div>

            <div className="bg-slate-950 border border-slate-200 rounded-lg overflow-x-auto">
              <table className="w-full border-collapse text-xs font-mono leading-6">
                <tbody>
                  {workflowLines.map((line, index) => (
                    <tr key={index} className="hover:bg-slate-900/70">
                      <td className="select-none text-right pr-4 pl-4 text-slate-500 tabular-nums w-12 align-top border-r border-slate-800/80">
                        {index + 1}
                      </td>
                      <td className="pl-4 pr-6 whitespace-pre text-slate-100">
                        {line.length > 0 ? line : ' '}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </section>
        )}

        {activeSection === 'install' && (
          <section className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div className="bg-white border border-slate-200 rounded-lg p-6 space-y-5">
              <div>
                <h2 className="text-lg font-semibold text-slate-900">
                  Installazione in WordPress 7.0 – 7.2
                </h2>
                <p className="text-sm text-slate-600 mt-1">
                  Il pacchetto scaricabile rispetta la struttura standard attesa da WordPress per l'upload diretto da pannello amministrativo.
                </p>
              </div>

              <ol className="space-y-4 text-sm text-slate-700">
                <li className="flex gap-3">
                  <span className="font-mono font-semibold text-slate-900 tabular-nums">01.</span>
                  <span>
                    Clicca su <strong>Scarica Plugin (.zip)</strong> per ottenere l'archivio <code className="font-mono text-xs bg-slate-100 px-1.5 py-0.5 rounded">prodotti-2.0.0.zip</code> contenente la cartella <code className="font-mono text-xs bg-slate-100 px-1.5 py-0.5 rounded">prodotti/</code>.
                  </span>
                </li>
                <li className="flex gap-3">
                  <span className="font-mono font-semibold text-slate-900 tabular-nums">02.</span>
                  <span>
                    Accedi alla bacheca WordPress (versione minima 7.0, PHP 8.3+), apri <strong>Plugin → Aggiungi nuovo → Carica plugin</strong> e seleziona lo zip.
                  </span>
                </li>
                <li className="flex gap-3">
                  <span className="font-mono font-semibold text-slate-900 tabular-nums">03.</span>
                  <span>
                    Attiva il plugin <strong>Prodotti</strong>: al primo avvio vengono registrati il post type <code className="font-mono text-xs bg-slate-100 px-1.5 py-0.5 rounded">product</code>, le 6 tassonomie e aggiornati automaticamente i permalink.
                  </span>
                </li>
                <li className="flex gap-3">
                  <span className="font-mono font-semibold text-slate-900 tabular-nums">04.</span>
                  <span>
                    Installa o attiva <strong>Meta Box</strong> quando richiesto dall'avviso di amministrazione per gestire i campi personalizzati di galleria, PDF e YouTube.
                  </span>
                </li>
              </ol>

              <div className="pt-2 flex flex-wrap items-center gap-3">
                <button
                  type="button"
                  onClick={() => handleDownloadZip(false)}
                  className="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#2271B1] hover:bg-[#135E96] rounded-lg transition-colors cursor-pointer"
                >
                  <Download className="w-4 h-4" />
                  <span>Scarica prodotti-2.0.0.zip</span>
                </button>
                <button
                  type="button"
                  onClick={() => handleDownloadZip(true)}
                  className="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 border border-slate-200 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer"
                >
                  <Download className="w-4 h-4" />
                  <span>Scarica con Workflow GitHub</span>
                </button>
              </div>
            </div>

            <div className="bg-white border border-slate-200 rounded-lg p-6 space-y-4">
              <div>
                <h2 className="text-lg font-semibold text-slate-900">
                  Struttura cartella organizzata
                </h2>
                <p className="text-sm text-slate-600 mt-1">
                  Architettura modulare compatibile con WordPress.org e GitHub Actions.
                </p>
              </div>

              <pre className="p-4 bg-slate-950 text-slate-100 rounded-md font-mono text-xs leading-6 overflow-x-auto">
{`README.md
.svnignore
.github/
└── workflows/
    └── wordpress-plugin.yml
prodotti/
├── prodotti.php
├── readme.txt
├── uninstall.php
├── assets/
│   └── img/
├── includes/
│   ├── prodotti-metabox.php
│   ├── prodotti-opzioni.php
│   ├── prodotti-pluginaggiuntivi.php
│   ├── prodotti-shortcode.php
│   ├── prodotti-tassonomie.php
│   └── prodotti-widget.php
└── languages/`}
              </pre>
            </div>
          </section>
        )}
      </main>
    </div>
  );
}
