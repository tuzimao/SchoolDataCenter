// ** React Imports
import { forwardRef, Ref, ReactElement, useState, useEffect, Fragment } from 'react'
import React, { useRef } from "react";

// ** MUI Imports
import Typography from '@mui/material/Typography'
import Box from '@mui/material/Box'
import Table from '@mui/material/Table'
import TableRow from '@mui/material/TableRow'
import TableBody from '@mui/material/TableBody'
import TableHead from '@mui/material/TableHead'
import TextField from '@mui/material/TextField'
import TableContainer from '@mui/material/TableContainer'
import TableCell from '@mui/material/TableCell'
import Grid from '@mui/material/Grid'
import Card from '@mui/material/Card'
import CardContent from '@mui/material/CardContent'
import Button from '@mui/material/Button'
import CircularProgress from '@mui/material/CircularProgress'
import { useTheme } from '@mui/material/styles'
import ArrowDropUpIcon from '@mui/icons-material/ArrowDropUp';
import ArrowDropDownIcon from '@mui/icons-material/ArrowDropDown';
import ViewTableList from './ViewTableList'

// ** Icon Imports
import {isMobile} from 'src/configs/functions'

// ** Config
import { defaultConfig } from 'src/configs/auth'
import axios from 'axios'

// ** Styles
import 'react-draft-wysiwyg/dist/react-draft-wysiwyg.css'

import { DecryptDataAES256GCM } from 'src/configs/functions'

import FormControl from '@mui/material/FormControl'
import Autocomplete from '@mui/material/Autocomplete'
import InputLabel from '@mui/material/InputLabel'
import Select from '@mui/material/Select'
import MenuItem from '@mui/material/MenuItem'

import IconButton from '@mui/material/IconButton'
import Dialog from '@mui/material/Dialog'
import DialogContent from '@mui/material/DialogContent'
import Fade, { FadeProps } from '@mui/material/Fade'
import Icon from 'src/@core/components/icon'

import jsPDF from 'jspdf'
import html2canvas from 'html2canvas'
import ExcelJS from "exceljs";
import { saveAs } from "file-saver"

const Transition = forwardRef(function Transition(
  props: FadeProps & { children?: ReactElement<any, any> },
  ref: Ref<unknown>
) {
  return <Fade ref={ref} {...props} />
})

interface ReportType {
  authConfig: any
  backEndApi: string
  report_default: any
}

const ReportCore = (props: ReportType) => {
  // ** Props
  const { authConfig, backEndApi, report_default } = props

  const theme = useTheme()

  const isMobileData = isMobile()
  console.log("isMobileData", isMobileData)

  // ** Hooks
  //const dispatch = useDispatch<AppDispatch>()
  const [isLoading, setIsLoading] = useState(false);
  const [reportData, setReportData] = useState<any>(null)
  const [currentButtonName, setCurrentButtonName] = useState<string>('')
  const [sortMethod, setSortMethod] = useState<number>(0)
  const [sortField, setSortField] = useState<string>('')
  const [isExporting, setIsExporting] = useState(false);
  const [openPrintPage, setOpenPrintPage] = useState(false);
  const [openDetailPage, setOpenDetailPage] = useState(false);
  const [xName, setXName] = useState<string>('')
  const [yValue, setYValue] = useState<string>('')

  const [searchData, setSearchData] = useState<any>(null)

  const ButtonList = report_default['ButtonList']

  console.log("searchData", searchData)

  const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
  const AccessKey = window.localStorage.getItem(defaultConfig.storageAccessKeyName)!

  useEffect(() => {
    if (backEndApi && backEndApi.length > 0 && report_default && report_default['ButtonList'] && report_default['ButtonList'][0] && report_default['ButtonList'][0]['code']) {
      setIsLoading(true)
      setSearchData(null)
      const currentReport = currentButtonName !== '' ? currentButtonName : report_default['ButtonList'][0]['code'] 
      setCurrentButtonName(currentReport)
      axios
        .post(
          authConfig.backEndApiHost + backEndApi + '?action=report_default&currentReport=' + currentReport,
          searchData,
          { headers: { Authorization: storedToken } }
        )      
        .then(res => {
          let dataJson: any = null
          const data = res.data
          if(data && data.isEncrypted == "1" && data.data)  {
              const i = data.data.slice(0, 32);
              const t = data.data.slice(-32);
              const e = data.data.slice(32, -32);
              const k = AccessKey;
              const DecryptDataAES256GCMData = DecryptDataAES256GCM(e, i, t, k)
              try {
                  dataJson = JSON.parse(DecryptDataAES256GCMData)
              }
              catch(Error: any) {
                  console.log("DecryptDataAES256GCMData view_default Error", Error)

                  dataJson = data
              }
          }
          else {

              dataJson = data
          }
          if (dataJson.status == "OK") {
            setReportData(dataJson)
            const 搜索条件 = dataJson['搜索区域']['搜索条件']
            const NewDefaultValue: any = {}
            搜索条件 && 搜索条件.length > 0 && 搜索条件.map((item: any)=>{
              NewDefaultValue[item.name] = item.default
            })
            setSearchData(NewDefaultValue)
            console.log("搜索条件", 搜索条件)
          }
          setIsLoading(false)
        })
        .catch(() => {
          setIsLoading(false)
          console.log("axios.get editUrl return")
        })
    }
  }, [currentButtonName, isMobileData])

  const handleSubmitData = async () => {

    setIsLoading(true)
    const currentReport = currentButtonName !== '' ? currentButtonName : report_default['ButtonList'][0]['code'] 
    axios
      .post(
        authConfig.backEndApiHost + backEndApi + '?action=report_default&currentReport=' + currentReport,
        {...searchData, system: 'search'},
        { headers: { Authorization: storedToken } }
      )  
      .then(res => {
        let dataJson: any = null
        const data = res.data
        if(data && data.isEncrypted == "1" && data.data)  {
            const i = data.data.slice(0, 32);
            const t = data.data.slice(-32);
            const e = data.data.slice(32, -32);
            const k = AccessKey;
            const DecryptDataAES256GCMData = DecryptDataAES256GCM(e, i, t, k)
            try {
                dataJson = JSON.parse(DecryptDataAES256GCMData)
            }
            catch(Error: any) {
                console.log("DecryptDataAES256GCMData view_default Error", Error)

                dataJson = data
            }
        }
        else {

            dataJson = data
        }
        if (dataJson.status == "OK") {
          setReportData(dataJson)
        }
        setIsLoading(false)
      })
      .catch(() => {
        setIsLoading(false)
        console.log("axios.get editUrl return")
      })

  }

  const handlePrintClose = () => {
    setOpenPrintPage(false)
  }

  const handleDetailClose = () => {
    setOpenDetailPage(false)
  }

  const isNumber = (value: any) => {
    return typeof value === 'number' || (!isNaN(value) && !isNaN(parseFloat(value)));
  }

  const handleFilterDataByColumnName = (ColumnName: string) => {

    const TempData = reportData['数据区域']['数据']
    console.log("TempData", TempData)
    TempData[0] && Object.keys(TempData[0]).includes(ColumnName) && TempData.sort((a: any, b: any) => {

      if(a[ColumnName] == null)   a[ColumnName] = '' ;
      if(b[ColumnName] == null)   b[ColumnName] = '' ;

      if(sortMethod % 2 == 0) {
        if(isNumber(a[ColumnName]) && isNumber(b[ColumnName])) {
          
          return a[ColumnName] - b[ColumnName];
        }
        else {
          
          return a[ColumnName].localeCompare(b[ColumnName], 'zh');
        }
      }
      else {
        if(isNumber(b[ColumnName]) && isNumber(a[ColumnName])) {
          
          return b[ColumnName] - a[ColumnName];
        }
        else {
          
          return b[ColumnName].localeCompare(a[ColumnName], 'zh');
        }
      }
    });

    if(TempData[0] && Object.keys(TempData[0]).includes(ColumnName)) {
      setSortMethod(sortMethod + 1)
      setSortField(ColumnName)
      setReportData(reportData)
    }

  }

  const tableRef = useRef<HTMLTableElement>(null);

  const ExportDataToExcel = async () => {
    const table = tableRef.current;
    if (!table) return;

    setIsExporting(true)

    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet("Sheet1");

    const rows = table.querySelectorAll("tr");
    const sheetData: (string | null)[][] = [];
    const occupied: Record<string, boolean> = {};

    rows.forEach((tr, rowIndex) => {
      sheetData[rowIndex] = sheetData[rowIndex] || [];
      let colIndex = 0;

      const cells = Array.from(tr.querySelectorAll("th, td"));
      cells.forEach((cell) => {
        while (occupied[`${rowIndex}_${colIndex}`]) {
          colIndex++;
        }

        const rowspan = parseInt(cell.getAttribute("rowspan") || "1", 10);
        const colspan = parseInt(cell.getAttribute("colspan") || "1", 10);

        sheetData[rowIndex][colIndex] = cell.textContent?.trim() || "";

        for (let r = rowIndex; r < rowIndex + rowspan; r++) {
          for (let c = colIndex; c < colIndex + colspan; c++) {
            if (r === rowIndex && c === colIndex) continue;
            occupied[`${r}_${c}`] = true;
          }
        }

        if (rowspan > 1 || colspan > 1) {
          worksheet.mergeCells(
            rowIndex + 1,
            colIndex + 1,
            rowIndex + rowspan,
            colIndex + colspan
          );
        }

        colIndex += colspan;
      });
    });

    // 插入数据及样式
    sheetData.forEach((rowData, rowIndex) => {
      const row = worksheet.getRow(rowIndex + 1);
      row.height = 20;
      rowData.forEach((value, colIndex) => {
        if (value !== null && value !== undefined) {
          const cell = row.getCell(colIndex + 1);
          cell.value = value;
          cell.alignment = { horizontal: "center", vertical: "middle" };
          cell.font = { name: "Arial", size: 12 };
          cell.border = {
            top: { style: "thin" },
            left: { style: "thin" },
            bottom: { style: "thin" },
            right: { style: "thin" },
          };
        }
      });
    });

    // 自动列宽：优先用第一行（header）长度，和所有单元格长度最大值
    worksheet.columns.forEach((column, colIndex) => {
      let maxLength = 10;

      // 先用第一行（表头）文本长度
      const headerCell = worksheet.getRow(1).getCell(colIndex + 1);
      const headerLength = headerCell.value ? headerCell.value.toString().length : 0;
      if (headerLength > maxLength) maxLength = headerLength;
      if (headerCell.value) {
        switch(headerCell.value.toString()) {
          case '序号':
            maxLength = 8
            break;
          case '系部':
          case '系部名称':
          case '专业':
          case '专业名称':
          case '班级':
          case '班级名称':
          case '部门':
          case '部门名称':
            maxLength = 16
            break;
        }
      }

      // 再用所有单元格文本最大长度
      column.eachCell && column.eachCell((cell) => {
        const len = cell.value ? cell.value.toString().length : 0;
        if (len > maxLength) maxLength = len;
      });

      // 设宽度，多留2个字符空间
      column.width = maxLength + 2;
    });

    // 导出
    const buffer = await workbook.xlsx.writeBuffer();
    const blob = new Blob([buffer], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    });
    
    const title = reportData?.['搜索区域']?.['标题'] ?? '导出数据';
    saveAs(blob, `${title}.xlsx`);

    setIsExporting(false)

  }

  const ExportDataToPDF = async () => {
    const table = tableRef.current;
    if (!table) return;

    setIsExporting(true)

    // 应用到所有单元格
    const originalStyle = table.style.cssText;
    table.style.border = '1px solid black';
    table.style.borderCollapse = 'separate';
    table.style.borderSpacing = '0px';
    table.style.boxSizing = 'border-box';
    const originalStyles: any[] = [];
    table.querySelectorAll('td, th').forEach((cell: any, index: number) => {
      originalStyles[index] = cell.style.cssText;
      cell.style.border = 'none';
      cell.style.boxShadow = 'inset 0 0 0 1px black';
      cell.style.backgroundColor = 'transparent';
      cell.style.color = 'black';
    });

    try {
      // Capture the table as an image using html2canvas
      const canvas = await html2canvas(table, {
        scale: 3,
        useCORS: true,
      });
      const imgData = canvas.toDataURL('image/png');

      // Create a new PDF document
      const pdf = new jsPDF('p', 'mm', 'a4');
      const imgWidth = 190; // Width of the image in the PDF
      const pageHeight = pdf.internal.pageSize.height;
      const imgHeight = (canvas.height * imgWidth) / canvas.width;

      let position = 10;
      
      // Add the image to the PDF
      pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);

      // If the image is longer than a page, add new pages
      while (position + imgHeight > pageHeight) {
        position -= pageHeight;
        pdf.addPage();
        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
      }

      // Save the PDF
      const title = reportData?.['搜索区域']?.['标题'] ?? '导出数据';
      pdf.save(`${title}.pdf`);

      // 恢复每个单元格的原始样式
      table.style.cssText = originalStyle;
      table.querySelectorAll('td, th').forEach((cell: any, index: number) => {
        cell.style.cssText = originalStyles[index];
      });

      setIsExporting(false)

    } catch (error) {
      console.error("Error exporting data to PDF:", error);

      setIsExporting(false)
    }
  };

  useEffect(() => {
    if (openPrintPage) {
      // 弹窗打开时，自动弹出打印窗口
      setTimeout(() => {
        window.print();
      }, 1000); // 延迟300ms，保证内容渲染完成
    }
  }, [openPrintPage]);

  console.log("reportData['数据区域']['头部']", reportData)

  const borderColor = theme.palette.mode === 'dark' ? theme.palette.grey[600] : theme.palette.grey[300]

  return (
    <Fragment>
      <Fragment>
        <Card sx={{mt: 1, pt: 1}}>
          <CardContent sx={{ mt: 0, pt: 0 }}>
            <Grid container justifyContent="flex-start" sx={{mt: 3}}>
              {ButtonList && ButtonList.length > 0 && ButtonList.map((item:any , index: number)=>(
                <Button key={index} onClick={()=>{
                  setCurrentButtonName(item.code)
                }} sx={{ mr: 2}} variant={item.code == currentButtonName || (currentButtonName == '' && index == 0) ? 'contained' : 'outlined'} size="small">{item.name}</Button>
              ))}
            </Grid>
            {reportData && reportData['搜索区域'] && reportData['搜索区域']['标题'] && (
              <Fragment>
                <Grid container spacing={2} sx={{mt: 0, mb: 2, p: 0}}>
                  <Grid item xs={12} sx={{p: 0, m: 0, mb: 1}}>
                    <Typography variant='body2'>
                      {reportData['搜索区域']['标题']}
                    </Typography>
                  </Grid>
                  {reportData['搜索区域'] && reportData['搜索区域']['搜索条件'] && reportData['搜索区域']['搜索条件'].map((cell: any, index: number) => {

                    return (
                      <Fragment key={index}>
                          {cell.type == 'input' && (
                              <Fragment>
                                <Grid item xs={12} sm={cell.sm} sx={{mb: 1}}>
                                  <TextField 
                                    size='small' 
                                    fullWidth 
                                    label={cell.name} 
                                    placeholder={cell.placeholder} 
                                    value={searchData && searchData[cell.name]}
                                    onChange={(e) => {
                                      setSearchData((prevData: any)=>({
                                        ...prevData,
                                        [cell.name]: e.target.value
                                      }));
                                    }}
                                  />
                                </Grid>
                            </Fragment>
                          )}
                          {cell.type == 'select' && (
                            <Fragment>
                              <Grid item xs={12} sm={cell.sm} sx={{mb: 1}}>
                                <FormControl size='small' fullWidth>
                                  <InputLabel id='form-layouts-separator-select-label'>{cell.name} </InputLabel>
                                  <Select
                                    label={cell.name}
                                    defaultValue={cell.default}
                                    id='form-layouts-separator-select'
                                    labelId='form-layouts-separator-select-label'
                                    onChange={(e) => {
                                      setSearchData((prevData: any)=>({
                                        ...prevData,
                                        [cell.name]: e.target.value
                                      }));
                                    }}
                                  >
                                    {cell.data && cell.data.map((item: any, itemIndex: number)=>{

                                      return <MenuItem value={item.value} key={itemIndex}>{item.name}</MenuItem>
                                    })}
                                  </Select>
                                </FormControl>
                              </Grid>
                            </Fragment>
                          )}
                          {cell.type == 'autocomplete' && (
                            <Fragment>
                              <Grid item xs={12} sm={cell.sm} sx={{mb: 1}}>
                                <Autocomplete
                                  size='small'
                                  fullWidth
                                  options={cell.data}
                                  defaultValue={{name: cell.default, value: cell.default}}
                                  id='autocomplete-outlined'
                                  getOptionLabel={(option: any) => option.name}
                                  renderInput={params => <TextField {...params} label={cell.name} />}
                                  onChange={(e: any, newValue: any) => {
                                    console.log("search e", newValue)
                                    if(newValue == null) {
                                      setSearchData((prevData: any)=>({
                                        ...prevData,
                                        [cell.name]: ''
                                      }));
                                    }
                                    else {
                                      setSearchData((prevData: any)=>({
                                        ...prevData,
                                        [cell.name]: newValue.value
                                      }));
                                    }
                                  }}
                                />
                              </Grid>
                            </Fragment>
                          )}
                          {cell.type == 'autocompletemulti' && (
                            <Fragment>
                              <Grid item xs={12} sm={cell.sm} sx={{mb: 1}}>
                                <Autocomplete
                                  size='small'
                                  fullWidth
                                  multiple
                                  options={cell.data}
                                  defaultValue={[{name: cell.default, value: cell.default}]}
                                  id='autocomplete-outlined'
                                  getOptionLabel={(option: any) => option.name}
                                  renderInput={params => <TextField {...params} label={cell.name} />}
                                  onChange={(e: any, newValue: any) => {
                                    console.log("search e", newValue)
                                    if (newValue && newValue.length > 0) {
                                      const newValueArray: string[] = []
                                      for (const fieldItem of newValue) {
                                          newValueArray.push(fieldItem.value);
                                      }
                                      setSearchData((prevData: any)=>({
                                        ...prevData,
                                        [cell.name]: newValueArray.join(',')
                                      }));
                                    }
                                    else {
                                      setSearchData((prevData: any)=>({
                                        ...prevData,
                                        [cell.name]: ''
                                      }));
                                    }
                                  }}
                                />
                              </Grid>
                            </Fragment>
                          )}
                      </Fragment>
                    )

                  })}
                </Grid>
                {reportData['搜索区域']['搜索按钮'] && reportData['搜索区域']['搜索条件'] && reportData['搜索区域']['搜索条件'].length > 0 && (
                  <Grid item xs={12} sx={{mb: 2}}>
                    <Button size='small' type='submit' sx={{ mr: 2 }} variant='contained' onClick={handleSubmitData} disabled={searchData == null ? true : false}>
                      {reportData['搜索区域']['搜索按钮']}
                    </Button>
                  </Grid>
                )}
              </Fragment>
            )}

            <TableDataAreaRender 
              reportData={reportData}
              isLoading={isLoading}
              tableRef={tableRef}
              borderColor={borderColor}
              handleFilterDataByColumnName={handleFilterDataByColumnName}
              sortField={sortField}
              sortMethod={sortMethod}
              setOpenDetailPage={setOpenDetailPage}
              setXName={setXName}
              setYValue={setYValue}
              />

            {reportData && reportData['底部区域'] && reportData['底部区域']['功能按钮'] && (
              <Grid container justifyContent="flex-end" sx={{mt: 3}}>
                {reportData['底部区域'] && reportData['底部区域']['功能按钮'] && reportData['底部区域']['功能按钮'].includes('打印') && (
                  <Button onClick={()=>setOpenPrintPage(true)}  variant='outlined' size="small" sx={{mr: 2}}>打印</Button>
                )}
                {reportData['底部区域'] && reportData['底部区域']['功能按钮'] && reportData['底部区域']['功能按钮'].includes('导出Excel') && (
                  <Button onClick={()=>ExportDataToExcel()} disabled={isExporting} variant='outlined' size="small" sx={{mr: 2}}>导出Excel</Button>
                )}
                {reportData['底部区域'] && reportData['底部区域']['功能按钮'] && reportData['底部区域']['功能按钮'].includes('导出Pdf') && (
                  <Button onClick={()=>ExportDataToPDF()} disabled={isExporting} variant='outlined' size="small" sx={{mr: 2}}>导出Pdf</Button>
                )}
              </Grid>
            )}

            {reportData != null && isLoading == true && (
              <Grid item xs={12} sm={12} container justifyContent="space-around">
                <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
                    <CircularProgress />
                    <Typography sx={{pt:5, pb:5}}>正在加载中</Typography>
                </Box>
              </Grid>
            )}
              
          </CardContent>
        </Card>
      </Fragment>

      {openPrintPage && (
        <Dialog
          fullWidth
          open={openPrintPage}
          maxWidth={'lg'}
          scroll='body'
          onClose={handlePrintClose}
          TransitionComponent={Transition}
        >
          <DialogContent sx={{ pb: 8, pl: { xs: 4, sm: 6 }, pr: { xs: 0, sm: 6 }, pt: { xs: 8, sm: 12.5 }, position: 'relative' }}>
            <IconButton
              size='small'
              onClick={handlePrintClose}
              sx={{ position: 'absolute', right: '1rem', top: '0.5rem' }}
            >
              <Icon icon='mdi:close' />
            </IconButton>
            <TableDataAreaRender 
              reportData={reportData}
              isLoading={isLoading}
              tableRef={tableRef}
              borderColor={borderColor}
              handleFilterDataByColumnName={handleFilterDataByColumnName}
              sortField={sortField}
              sortMethod={sortMethod}
              />
          </DialogContent>
        </Dialog >
      )}

      {openDetailPage && xName && xName.length > 0 && yValue && currentButtonName && (
        <Dialog
          fullWidth
          open={openDetailPage}
          maxWidth={'lg'}
          scroll='body'
          onClose={handleDetailClose}
          TransitionComponent={Transition}
        >
          <DialogContent sx={{ pb: 8, pl: { xs: 4, sm: 6 }, pr: { xs: 0, sm: 6 }, pt: { xs: 8, sm: 12.5 }, position: 'relative' }}>
            <IconButton
              size='small'
              onClick={handleDetailClose}
              sx={{ position: 'absolute', right: '1rem', top: '0.5rem' }}
            >
              <Icon icon='mdi:close' />
            </IconButton>
            <ViewTableList
              authConfig={authConfig}
              backEndApi={backEndApi}
              currentReport={currentButtonName}
              xName={xName}
              yValue={yValue}
              />
          </DialogContent>
        </Dialog >
      )}

      {reportData == null && isLoading == true && (
        <Grid item xs={12} sm={12} container justifyContent="space-around">
          <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
              <CircularProgress />
              <Typography sx={{pt:5, pb:5}}>正在加载中</Typography>
          </Box>
        </Grid>
      )}
    </Fragment>
  )
}

const TableDataAreaRender = ({ reportData, isLoading, tableRef, borderColor, handleFilterDataByColumnName, sortField, sortMethod, setOpenDetailPage, setXName, setYValue}: any) => {

  return (
    <Fragment>
      {isLoading == false && reportData && (
        <Fragment>
          <TableContainer sx={{ maxHeight: 800 }}>
            <Table 
              ref={tableRef}
              stickyHeader
              sx={{
                borderCollapse: 'collapse',
                border: `1px solid ${borderColor}`, 
                '& td, & th': {
                  border: `1px solid ${borderColor}`,
                },
              }}
            >
              {/* First header row */}
              <TableHead>
                <TableRow>
                  {reportData && reportData['数据区域'] && reportData['数据区域']['头部'] && reportData['数据区域']['头部'][1] && reportData['数据区域']['头部'][0].map((cell: any, index: number) => (
                    <TableCell
                      key={`header1-${index}`}
                      rowSpan={cell.row}
                      colSpan={cell.col}
                      sx={{                                
                        whiteSpace: cell.wrap == 'No' ? 'nowrap' : 'pre-line',
                        wordBreak: cell.wrap == 'Yes' ? 'break-word' : 'normal',
                        textAlign: cell.align == 'Center' ? 'center' : 'left',
                        fontWeight: 'bold',
                        position: 'sticky',
                        top: 0,
                        mx: '0 !important',
                        my: '0 !important',
                        py: '4px !important',
                        px: '8px !important',
                        cursor: cell.col == 1 ? 'pointer' : 'auto',
                      }}
                      onClick={()=>handleFilterDataByColumnName(cell.name)}
                    >
                      {cell.name}
                      {sortField === cell.name && cell.col == 1 && (
                        sortMethod % 2 == 0 ? <ArrowDropUpIcon sx={{ verticalAlign: 'middle' }} /> : <ArrowDropDownIcon sx={{ verticalAlign: 'middle' }} />
                      )}
                    </TableCell>
                  ))}
                </TableRow>
                <TableRow>
                  {reportData && reportData['数据区域'] && reportData['数据区域']['头部'] && reportData['数据区域']['头部'][1] && reportData['数据区域']['头部'][1].map((cell: any, index: number) => (
                    <TableCell
                      key={`header2-${index}`}
                      rowSpan={cell.row}
                      colSpan={cell.col}
                      sx={{                                
                        whiteSpace: cell.wrap == 'No' ? 'nowrap' : 'pre-line',
                        wordBreak: cell.wrap == 'Yes' ? 'break-word' : 'normal',
                        textAlign: cell.align == 'Center' ? 'center' : 'left',
                        fontWeight: 'bold',
                        position: 'sticky',
                        top: 29,
                        mx: '0 !important',
                        my: '0 !important',
                        py: '4px !important',
                        px: '8px !important',
                        cursor: cell.col == 1 ? 'pointer' : 'auto',
                      }}
                      onClick={()=>handleFilterDataByColumnName(cell.name)}
                    >
                      {cell.name}
                      {sortField === cell.name && cell.col == 1 && (
                        sortMethod % 2 == 0 ? <ArrowDropUpIcon sx={{ verticalAlign: 'middle' }} /> : <ArrowDropDownIcon sx={{ verticalAlign: 'middle' }} />
                      )}
                    </TableCell>
                  ))}
                </TableRow>
              </TableHead>

              {/* Table body */}
              <TableBody>
                {reportData && reportData['数据区域'] && reportData['数据区域']['头部'] && reportData['数据区域']['头部'][1] && reportData['数据区域']['数据'].map((cell: any, rowIndex: number) => (
                  <TableRow key={`row-${rowIndex}`} >
                    {Object.keys(cell).map((key, cellIndex) => (
                      <TableCell
                        key={`cell-${rowIndex}-${cellIndex}`}
                        rowSpan={cell.row}
                        colSpan={cell.col}
                        sx={{                                
                          whiteSpace: cell.wrap == 'No' ? 'nowrap' : 'pre-line',
                          wordBreak: cell.wrap == 'Yes' ? 'break-word' : 'normal',
                          textAlign: cell.align == 'Center' ? 'center' : 'center',
                          mx: '0 !important',
                          my: '0 !important',
                          py: '6px !important',
                          px: '0 !important'
                        }}
                      >
                        {reportData['数据区域']['链接'] != true && cell[key]}
                        {reportData['数据区域']['链接'] == true && (
                          <Typography variant='body2' sx={{ my: 'auto', textAlign: 'center', cursor: 'pointer' }} onClick={()=>{
                            setOpenDetailPage(true)
                            setXName(key)
                            setYValue(cell[reportData['数据区域']['右侧数据关联字段']] || '')
                          }}>{cell[key]}</Typography>
                        )}
                      </TableCell>
                    ))}
                  </TableRow>
                ))}
                {reportData && reportData['数据区域'] && reportData['数据区域']['头部'] && reportData['数据区域']['头部'][1] == null && (
                  <TableRow  >
                    <TableCell
                        sx={{      
                          textAlign: 'center',                          
                          mx: '0 !important',
                          my: '0 !important',
                          py: '6px !important',
                          px: '0 !important'
                        }}
                      >
                        无数据
                      </TableCell>
                  </TableRow>
                )}
              </TableBody>
            </Table>
            {reportData['底部区域'] && reportData['底部区域']['备注'] && reportData['底部区域']['备注']['标题'] && (
              <Table 
                sx={{
                  borderCollapse: 'collapse',
                  border: `1px solid ${borderColor}`, 
                  '& td, & th': {
                    border: `1px solid ${borderColor}`,
                  },
                  mt: 5,
                  mb: 4
                }}
              >
                <TableHead>
                  <TableRow>
                    <TableCell
                        sx={{
                          px: 1,
                          py: 2,
                        }}
                      >
                        {reportData['底部区域']['备注']['标题']}
                    </TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  <TableRow>
                    <TableCell
                        sx={{
                          px: 1,
                          py: 2,
                          whiteSpace: 'pre-line',
                          wordBreak: 'break-word',
                        }}
                      >
                        {reportData['底部区域']['备注']['内容']}
                    </TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            )}
          </TableContainer>
        </Fragment>
      )}
    </Fragment>
  );
};


export default ReportCore
