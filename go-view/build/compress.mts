import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import archiver from 'archiver';

// 获取当前模块的目录路径
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/**
 * 将指定目录压缩为 ZIP 文件
 * @param sourceDir 要压缩的目录（绝对路径）
 * @param outputZip 输出 ZIP 文件的绝对路径
 */
function zipDirectory(sourceDir: string, outputZip: string): Promise<void> {
  return new Promise((resolve, reject) => {
    if (!fs.existsSync(sourceDir)) {
      return reject(new Error(`目录不存在: ${sourceDir}`));
    }

    const output = fs.createWriteStream(outputZip);
    const archive = archiver('zip', { zlib: { level: 9 } });

    // 出现错误时 reject
    archive.on('error', (err) => reject(err));

    // 压缩完成时 resolve
    output.on('close', () => {
      console.log(`✅ 压缩完成: ${outputZip} (共 ${archive.pointer()} 字节)`);
      resolve();
    });

    // 连接写入流
    archive.pipe(output);

    // 将整个目录（不包含根目录）添加到 ZIP 中
    archive.directory(sourceDir, false);

    archive.finalize();
  });
}

// 这里指定要压缩的目录与输出的 ZIP 路径
const OUTPUT_DIR = path.resolve(__dirname, '../dist');
const ZIP_PATH = path.resolve(__dirname, '../../htdocs/goview/goview_html.zip');

zipDirectory(OUTPUT_DIR, ZIP_PATH).catch((err) => {
  console.error('❌ 压缩失败:', err);
});