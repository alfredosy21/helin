import esbuild from 'esbuild';
import { readdir, mkdir, writeFile, readFile } from 'fs/promises';
import { join, dirname, relative } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const root = join(__dirname, '..');

const targets = [
    { dir: 'public/helin/css', ext: '.css' },
    { dir: 'public/helin/js', ext: '.js' },
];

async function getFiles(dir, ext) {
    const entries = await readdir(dir, { withFileTypes: true });
    return entries
        .filter(e => e.isFile() && e.name.endsWith(ext) && !e.name.endsWith('.min' + ext))
        .map(e => join(dir, e.name));
}

async function minifyFile(filePath, ext) {
    const absPath = join(root, filePath);
    const content = await readFile(absPath, 'utf8');
    if (!content.trim()) return null;

    const result = await esbuild.transform(content, {
        loader: ext === '.css' ? 'css' : 'js',
        minify: true,
        target: ext === '.css' ? undefined : 'es2020',
        legalComments: 'none',
    });

    const outPath = absPath.replace(ext, '.min' + ext);
    await writeFile(outPath, result.code, 'utf8');
    return { file: filePath, out: relative(root, outPath), size: content.length, minSize: result.code.length };
}

async function main() {
    console.log('Minificando CSS y JS...\n');
    let totalOriginal = 0;
    let totalMinified = 0;

    for (const { dir, ext } of targets) {
        const files = await getFiles(dir, ext);
        for (const file of files) {
            try {
                const r = await minifyFile(file, ext);
                if (r) {
                    const saved = ((1 - r.minSize / r.size) * 100).toFixed(1);
                    console.log(`  ${r.file} -> ${r.out}  (${(r.size/1024).toFixed(1)}KB -> ${(r.minSize/1024).toFixed(1)}KB, -${saved}%)`);
                    totalOriginal += r.size;
                    totalMinified += r.minSize;
                }
            } catch (e) {
                console.error(`  Error en ${file}: ${e.message}`);
            }
        }
    }

    const totalSaved = ((1 - totalMinified / totalOriginal) * 100).toFixed(1);
    console.log(`\nTotal: ${(totalOriginal/1024).toFixed(1)}KB -> ${(totalMinified/1024).toFixed(1)}KB (-${totalSaved}%)`);
}

main().catch(console.error);
