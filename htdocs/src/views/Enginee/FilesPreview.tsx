// ** React Imports
import { forwardRef, ReactElement, Ref, Fragment, useState, useEffect, SetStateAction, useRef } from 'react'

// ** MUI Imports
import Box from '@mui/material/Box'
import Badge from '@mui/material/Badge'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import IconButton from '@mui/material/IconButton'
import Dialog from '@mui/material/Dialog'
import DialogContent from '@mui/material/DialogContent'
import Fade, { FadeProps } from '@mui/material/Fade'
import styles from './components/Excel2007.module.css'
import Container from '@mui/material/Container'
import CircularProgress from '@mui/material/CircularProgress'
import Icon from 'src/@core/components/icon'

//PDF
import { pdfjs, Document, Page } from 'react-pdf';

import 'react-pdf/dist/esm/Page/AnnotationLayer.css';
import 'react-pdf/dist/esm/Page/TextLayer.css';

pdfjs.GlobalWorkerOptions.workerSrc = `//cdnjs.cloudflare.com/ajax/libs/pdf.js/${pdfjs.version}/pdf.worker.js`;

//EXCEL
import {OutTable, ExcelRenderer} from 'react-excel-renderer';

//Word
import { renderAsync } from 'docx-preview';

//PPTX
import { init } from 'pptx-preview';


const Transition = forwardRef(function Transition(
    props: FadeProps & { children?: ReactElement<any, any> },
    ref: Ref<unknown>
  ) {
    return <Fade ref={ref} {...props} />
  })

// ** Third Party Components
import clsx from 'clsx'
import { useKeenSlider } from 'keen-slider/react'

function ExcelViewer({ fileUrl }: { fileUrl: string; } ) {
  const [rows, setRows] = useState([]);
  const [cols, setCols] = useState([]);

  const [loading, setLoading] = useState<boolean>(true)
  const loadingText = 'Loading'

  useEffect(() => {
    const fetchExcel = async () => {
      try {
        const response = await fetch(fileUrl);
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        
        const blob = await response.blob();
        
        const reader = new FileReader();
        reader.onload = () => {
          ExcelRenderer(blob, (err: any, resp: { cols: SetStateAction<never[]>; rows: SetStateAction<never[]> }) => {
            if (err) {
              console.error(err);
            } 
            else {
              const tempCols: SetStateAction<any[]> = []
              tempCols.push({name: '', key: 0})
              
              // @ts-ignore
              resp && resp.cols && resp.cols.map((Item: {'name': string, 'key': number}, Index: number)=>{
                if(Item.name) {
                  tempCols.push({name: Item.name, key: Index+1})
                }
              })
              
              // @ts-ignore
              setCols(tempCols);
              
              // @ts-ignore
              setRows(resp.rows);

              setLoading(false)
            }
          });
        };
        
        reader.onerror = () => {
          setLoading(false)
          throw new Error("Failed to read the blob data");
        };
        
        reader.readAsBinaryString(blob);
      } catch (error) {
        setLoading(false)
        console.error("Error fetching or parsing the Excel file:", error);
      }
    };

    fetchExcel();
  }, [fileUrl]);

  return (
      <div style={{ 
        maxHeight: '85vh', // 限制最大高度
        overflow: 'auto',  // 启用滚动
        width: '100%'      // 确保宽度填满容器
      }}>
        {rows && cols && loading == false && (
          <OutTable
            data={rows}
            columns={cols}
            tableClassName={styles.ExcelTable2007}
            tableHeaderRowClass={styles.heading}
          />
        )}
        {loading &&
              <Container>
                <Grid container spacing={2}>
                  <Grid item xs={12} sx={{}}>
                    <Box sx={{ mx: 6, display: 'flex', alignItems: 'center', flexDirection: 'column', whiteSpace: 'nowrap' }}>
                      <CircularProgress sx={{ mb: 8 }} />
                      <Typography>{loadingText}</Typography>
                    </Box>
                  </Grid>
                </Grid>
              </Container>
        }
      </div>
  );
}

function WordViewer({ fileUrl }: { fileUrl: string }) {
  const previewRef = useRef<HTMLDivElement>(null);
  const [loading, setLoading] = useState<boolean>(true)
  const loadingText = 'Loading'

  useEffect(() => {
    const fetchAndRender = async () => {
      try {
        const response = await fetch(fileUrl);
        const arrayBuffer = await response.arrayBuffer();
        setLoading(false)
        if (previewRef.current) {
          await renderAsync(arrayBuffer, previewRef.current);
        }
      } catch (error) {
        console.error('Error rendering Word file:', error);
      }
    };

    fetchAndRender();
  }, [fileUrl]);

  return (
    <>
    <div 
      ref={previewRef}
      style={{ 
        maxHeight: '85vh', 
        overflow: 'auto',
        width: '100%'
      }} 
    />
    {loading && (
      <Container>
        <Grid container spacing={2}>
          <Grid item xs={12} sx={{}}>
            <Box sx={{ mx: 6, display: 'flex', alignItems: 'center', flexDirection: 'column', whiteSpace: 'nowrap' }}>
              <CircularProgress sx={{ mb: 8 }} />
              <Typography>{loadingText}</Typography>
            </Box>
          </Grid>
        </Grid>
      </Container>
    )}
    </>
  );
}

function PDFViewer({ fileUrl }: { fileUrl: string }) {
  const [numPages, setNumPages] = useState<number>(0);
  const [loading, setLoading] = useState<boolean>(true);
  const [containerWidth, setContainerWidth] = useState<number>(800);

  function onDocumentLoadSuccess({ numPages }: { numPages: number }) {
    setNumPages(numPages);
    setLoading(false);
  }

  // 计算页面宽度，保持A4比例 (1.414)
  const getPageWidth = () => {
    return Math.min(containerWidth, 800);
  };

  console.log("PDFViewer loading", loading)

  return (
    <div 
      style={{ 
        maxHeight: '85vh',
        overflow: 'auto',
        width: '100%',
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
      }}
      ref={(ref) => {
        if (ref) {
          setContainerWidth(ref.clientWidth);
        }
      }}
    >
      <Document
        file={fileUrl}
        onLoadSuccess={onDocumentLoadSuccess}
        loading={<div>加载中...</div>}
        error={<div>加载失败！<a href={fileUrl} download>点击下载</a></div>}
      >
        {Array.from(new Array(numPages), (_, index) => (
          <div 
            key={`page_${index + 1}`}
            style={{
              marginBottom: '8px',
              boxShadow: '0 2px 8px rgba(0,0,0,0.1)'
            }}
          >
            <Page 
              pageNumber={index + 1} 
              width={getPageWidth()}
              renderTextLayer={true}
              renderAnnotationLayer={true}
            />
            {index < numPages - 1 && (
              <div style={{ 
                height: '1px', 
                backgroundColor: '#e0e0e0',
                margin: '16px 0'
              }} />
            )}
          </div>
        ))}
      </Document>
    </div>
  );
}

interface PPTXViewerProps {
  fileUrl?: string;  // 支持直接传入URL
  fileData?: ArrayBuffer; // 或者直接传入ArrayBuffer数据
  width?: number;
  height?: number;
  className?: string;
}

const PPTXViewer: React.FC<PPTXViewerProps> = ({
  fileUrl = 'test.pptx',
  fileData,
  width = 850,
  height = 800,
  className = ''
}) => {
  const containerRef = useRef<HTMLDivElement>(null);
  const previewerRef = useRef<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // 加载并预览PPTX文件
  const loadAndPreview = async (arrayBuffer: ArrayBuffer) => {
    try {
      if (!containerRef.current) {
        throw new Error('PPTX容器未初始化');
      }

      // 清理旧实例
      if (previewerRef.current) {
        previewerRef.current.destroy();
      }

      // 初始化预览器
      previewerRef.current = init(containerRef.current, {
        width,
        height
      });

      // 执行预览
      previewerRef.current.preview(arrayBuffer);
      setLoading(false);
    } catch (err) {
      console.error('PPTX预览失败:', err);
      setError(err instanceof Error ? err.message : '未知错误');
      setLoading(false);
    }
  };

  // 从URL获取文件
  const fetchFile = async (url: string) => {
    setLoading(true);
    setError(null);
    
    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`HTTP错误: ${response.status}`);
      }

      return await response.arrayBuffer();
    } catch (err) {
      console.error('文件加载失败:', err);
      setError(err instanceof Error ? err.message : '文件加载失败');
      setLoading(false);
      
      return null;
    }
  };

  // 主效果钩子
  useEffect(() => {
    if (fileData) {
      // 如果直接提供了文件数据
      loadAndPreview(fileData);
    } else if (fileUrl) {
      // 通过URL加载文件
      fetchFile(fileUrl).then(arrayBuffer => {
        if (arrayBuffer) {
          loadAndPreview(arrayBuffer);
        }
      });
    }

    // 清理函数
    return () => {
      if (previewerRef.current) {
        previewerRef.current.destroy();
        previewerRef.current = null;
      }
    };
  }, [fileUrl, fileData, width, height]);

  return (
    <div className={`pptx-viewer-container ${className}`}>
      {loading && (
        <div className="pptx-loading">
          <div className="spinner" />
          <p>正在加载PPTX文档...</p>
        </div>
      )}
      
      {error && (
        <div className="pptx-error">
          <h3>⚠️ 加载失败</h3>
          <p>{error}</p>
          <button 
            onClick={() => fileUrl && fetchFile(fileUrl).then(arrayBuffer => {
              if (arrayBuffer) loadAndPreview(arrayBuffer);
            })}
          >
            重试
          </button>
        </div>
      )}

      <div
        ref={containerRef}
        className="pptx-wrapper"
        style={{
          display: error ? 'none' : 'block',
          width: `${width}px`,
          height: `${height}px`
        }}
      />
    </div>
  );
};


interface ImagesPreviewType {
  open: boolean
  imagesList: string[]
  imagesType: string[]
  toggleImagesPreviewDrawer: () => void
}

const FilesPreview = (props: ImagesPreviewType) => {
  // ** Props
  const { open, imagesList, imagesType, toggleImagesPreviewDrawer } = props
  console.log("FilesPreview", imagesList)

  const handleClose = () => {
    toggleImagesPreviewDrawer()
  }

  //const [numPages, setNumPages] = useState<number>(0)
  //function onDocumentLoadSuccess({ numPages }: { numPages: number; } ) {
  //    setNumPages(numPages);
  //}

  // ** States
  const [loaded, setLoaded] = useState<boolean>(false)
  const [currentSlide, setCurrentSlide] = useState<number>(0)

  // ** Hook
  const [sliderRef, instanceRef] = useKeenSlider<HTMLDivElement>({
    rtl: true,
    slideChanged(slider) {
      setCurrentSlide(slider.track.details.rel)
    },
    created() {
      setLoaded(true)
    }
  })

  return (
    <Dialog
        fullWidth
        open={open}
        maxWidth='md'
        scroll='body'
        onClose={handleClose}
        TransitionComponent={Transition}
      >
        <DialogContent sx={{ pb: 8, px: { xs: 8, sm: 8 }, pt: { xs: 8, sm: 12.5 }, position: 'relative' }}>
          <IconButton
            size='small'
            onClick={handleClose}
            sx={{ position: 'absolute', right: '0.3rem', top: '0.3rem' }}
          >
            <Icon icon='mdi:close' />
          </IconButton>
          <Fragment>
            <Box className='navigation-wrapper'>
                <Box ref={sliderRef} className='keen-slider'>
                {imagesList && imagesList.length>0 && imagesList.map((Url: string, UrlIndex: number)=>{
                  switch(imagesType[UrlIndex]) {
                    case 'image':

                    return (
                          <Box className='keen-slider__slide' key={UrlIndex}>
                              <img src={Url} style={{'width':'100%', 'borderRadius': '4px'}}/>
                          </Box>
                      )
                    case 'pdf':

                      return <PDFViewer fileUrl={Url} />

                    case 'Word':
                      
                      return <WordViewer fileUrl={Url} />

                    case 'Excel':

                      return <ExcelViewer fileUrl={Url} />

                    case 'PowerPoint':

                      return <PPTXViewer fileUrl={Url} />

                    default:

                      return (
                          <Box className='keen-slider__slide' key={UrlIndex}>
                              <img src={Url} style={{'width':'100%', 'borderRadius': '4px'}}/>
                          </Box>
                      )
                  }
                })}
                </Box>
                {imagesList && imagesList[0]=="image" && loaded && instanceRef.current && (
                  <Fragment>
                      <Icon
                      icon='mdi:chevron-left'
                      className={clsx('arrow arrow-left', {
                          'arrow-disabled': currentSlide === 0
                      })}
                      onClick={(e: any) => { e.stopPropagation(); instanceRef.current && instanceRef.current.prev(); }}
                      />
                      <Icon
                      icon='mdi:chevron-right'
                      className={clsx('arrow arrow-right', {
                          'arrow-disabled': currentSlide === instanceRef.current.track.details.slides.length - 1
                      })}
                      onClick={(e: any) => { e.stopPropagation(); instanceRef.current && instanceRef.current.next(); }}
                      />
                  </Fragment>
                )}
            </Box>
            {imagesList && imagesList[0]=="image" && loaded && instanceRef.current && (
                <Box className='swiper-dots'>
                {[...Array(instanceRef.current.track.details.slides.length).keys()].map(idx => {

                    return (
                      <Badge
                          key={idx}
                          variant='dot'
                          component='div'
                          className={clsx({
                          active: currentSlide === idx
                          })}
                          onClick={() => {
                            if (instanceRef.current) {
                              instanceRef.current.moveToIdx(idx);
                            }
                          }}

                      ></Badge>
                    )
                })}
                </Box>
            )}
            </Fragment>
        </DialogContent>
      </Dialog >

  )
}

export default FilesPreview
