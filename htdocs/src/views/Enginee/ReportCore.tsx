// ** React Imports
import { useState, useEffect, Fragment } from 'react'

// ** MUI Imports
import Typography from '@mui/material/Typography'
import Box from '@mui/material/Box'
import Table from '@mui/material/Table'
import TableRow from '@mui/material/TableRow'
import TableBody from '@mui/material/TableBody'
import TableHead from '@mui/material/TableHead'
import Paper from '@mui/material/Paper'
import TextField from '@mui/material/TextField'
import TableContainer from '@mui/material/TableContainer'
import { styled } from '@mui/material/styles'
import TableCell, { TableCellBaseProps } from '@mui/material/TableCell'
import Grid from '@mui/material/Grid'
import Card from '@mui/material/Card'
import CardContent from '@mui/material/CardContent'
import ListItem from '@mui/material/ListItem'
import Button from '@mui/material/Button'
import CircularProgress from '@mui/material/CircularProgress'
import { useTheme } from '@mui/material/styles'

// ** Icon Imports
import Icon from 'src/@core/components/icon'
import {isMobile} from 'src/configs/functions'

// ** Config
import { defaultConfig } from 'src/configs/auth'
import axios from 'axios'
import Mousetrap from 'mousetrap';

// ** Store Imports
import { useSelector } from 'react-redux'

// ** Styles
import 'react-draft-wysiwyg/dist/react-draft-wysiwyg.css'

import { RootState } from 'src/store/index'
import { Divider } from '@mui/material'
import { DecryptDataAES256GCM } from 'src/configs/functions'

// ** Next Imports
import Link from 'next/link'

import FormControl from '@mui/material/FormControl'
import FormLabel from '@mui/material/FormLabel'
import Radio from '@mui/material/Radio'
import RadioGroup from '@mui/material/RadioGroup'
import OutlinedInput from '@mui/material/OutlinedInput'
import FormHelperText from '@mui/material/FormHelperText'
import InputAdornment from '@mui/material/InputAdornment'
import FormControlLabel from '@mui/material/FormControlLabel'
import Autocomplete from '@mui/material/Autocomplete'
import Tooltip from "@mui/material/Tooltip"
import IconButton from '@mui/material/IconButton'
import HelpIcon from '@mui/icons-material/Help'
import InputLabel from '@mui/material/InputLabel'

const MUITableCell = styled(TableCell)<TableCellBaseProps>(({ theme }) => ({
  borderBottom: 0,
  paddingLeft: '0 !important',
  paddingRight: '0 !important',
  paddingTop: `${theme.spacing(1)} !important`,
  paddingBottom: `${theme.spacing(1)} !important`
}))

interface ReportType {
  action: string
  backEndApi: string
  editViewCounter: number
  authConfig: any
  externalId: number
}

const ImgStyled = styled('img')(({ theme }) => ({
  width: 120,
  borderRadius: 4,
  marginRight: theme.spacing(5)
}))

const ImgStyled68 = styled('img')(({ theme }) => ({
  width: 65,
  borderRadius: 4,
  marginRight: theme.spacing(1)
}))

const CustomLink = styled(Link)({
  textDecoration: "none",
  color: "inherit",
});

const StyledTableCell = styled(TableCell)(({ theme }) => ({
  border: '1px solid rgba(224, 224, 224, 1)',
  padding: theme.spacing(1),
  textAlign: 'center',
}));

const HeaderRowSpanCell = styled(StyledTableCell)({
  fontWeight: 'bold',
  backgroundColor: '#f5f5f5',
});

const ReportCore = (props: ReportType) => {
  // ** Props
  const { authConfig, externalId, action, backEndApi, editViewCounter } = props
  console.log("externalId props", externalId)

  const theme = useTheme()

  const isMobileData = isMobile()
  console.log("isMobileData", isMobileData)

  // ** Hooks
  //const dispatch = useDispatch<AppDispatch>()
  const [isLoading, setIsLoading] = useState(false);
  const store = useSelector((state: RootState) => state.user)
  const titletext: string = store.view_default.titletext
  const [reportData, setReportData] = useState<any>(null)

  const [searchData, setSearchData] = useState<any>(null)
  console.log("searchData", searchData)

  useEffect(() => {
    Mousetrap.bind(['alt+c', 'command+c'], handleClose);

    return () => {
      Mousetrap.unbind(['alt+c', 'command+c']);
    }
  });

  const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
  const AccessKey = window.localStorage.getItem(defaultConfig.storageAccessKeyName)!

  useEffect(() => {
    if (action == "report_default" && editViewCounter > 0) {
      setIsLoading(true)
      axios
        .get(authConfig.backEndApiHost + backEndApi, { headers: { Authorization: storedToken }, params: { action, editViewCounter, isMobileData } })
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
  }, [editViewCounter, isMobileData])

  //Need refresh data every time.
  interface FileUrl extends File {
    url: string;
  }

  const handleSubmitData = async () => {

    setIsLoading(true)
      axios
        .get(authConfig.backEndApiHost + backEndApi + "?" + reportData['搜索区域']['搜索事件'], { headers: { Authorization: storedToken }, params: searchData })
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

  const renderFilePreview = (file: File | FileUrl, width: number, height: number) => {
    if (file && 'webkitRelativePath' in file && file['webkitRelativePath']!="" && file['type']=="image") {
        return <Box sx={{m: 0, p: 0, cursor: 'pointer'}} ><img width={width} height={height} alt={file.name} style={{padding: "1px"}} src={authConfig.backEndApiHost+file['webkitRelativePath']} /></Box>
    }
    else if (file && 'webkitRelativePath' in file && file['webkitRelativePath']!="" && file['type']=="Word") {
        return <Icon icon='icon-park-outline:word' />
    }
    else if (file && 'webkitRelativePath' in file && file['webkitRelativePath']!="" && file['type']=="Excel") {
        return <Icon icon='icon-park-outline:excel' />
    }
    else if (file && 'webkitRelativePath' in file && file['webkitRelativePath']!="" && file['type']=="PowerPoint") {
        return <Icon icon='teenyicons:ppt-outline' />
    }
    else if (file && 'webkitRelativePath' in file && file['webkitRelativePath']!="" && file['type']=="pdf") {
        return <Icon icon='icomoon-free:file-pdf' />
    }
    else if (file && 'webkitRelativePath' in file && file['webkitRelativePath']!="" && file['type']!="image") {
        return <Icon icon='mdi:file-document-outline' />
    }
    else if (file.type.startsWith('image')) {
        return <img width={width} height={height} alt={file.name} style={{padding: "1px"}} src={URL.createObjectURL(file as any)} />
    }
    else {
        return <Icon icon='mdi:file-document-outline' />
    }
  }
  const renderFilePreviewLink = (fileInfor: File | FileUrl) => {
    if(fileInfor['type']=="file" || fileInfor['type']=="Word" || fileInfor['type']=="Excel" || fileInfor['type']=="PowerPoint" || fileInfor['type']=="pdf" || fileInfor['type']=="image")  {

        return (
            <Typography className='file-name'>
                <Box sx={{m: 0, p: 0, cursor: 'pointer'}} >{fileInfor['name']}</Box>
            </Typography>
        )
    }
    else {
        
        return (
            <Typography className='file-name'>{fileInfor['name']}</Typography>
        )
    }
  }
    
  const handleClose = () => {
    console.log("")
  }

  console.log("reportData['数据区域']['头部']", reportData)

  const borderColor = theme.palette.mode === 'dark' ? theme.palette.grey[600] : theme.palette.grey[300]

  return (
    <Fragment>
      {reportData && (
        <Fragment>
          <Card sx={{mt: 1, pt: 1}}>
            <CardContent sx={{ mt: 0, pt: 0 }}>
              <Grid container spacing={2} sx={{mt: 0, mb: 2, p: 0}}>
                <Grid item xs={12} sx={{p: 0, m: 0}}>
                  <Typography variant='body2'>
                    {reportData['搜索区域']['标题']}
                  </Typography>
                </Grid>
                {reportData['搜索区域'] && reportData['搜索区域']['搜索条件'] && reportData['搜索区域']['搜索条件'].map((cell: any, index: number) => {

                  return (
                    <Fragment key={index}>
                        {cell.type == 'input' && (
                            <Fragment>
                              <Grid item xs={12} sm={4}>
                                <TextField 
                                  size='small' 
                                  fullWidth 
                                  label={cell.name} 
                                  placeholder={cell.placeholder} 
                                  onChange={(e) => {
                                    setSearchData((prevData: any)=>({
                                      ...prevData,
                                      [cell.name]: e.target.value
                                    }));
                                  }}
                                />
                              </Grid>
                              {cell.helptext && cell.helptext.length>12 && (
                                  <FormHelperText sx={{mx: 0.5}}>
                                      <Tooltip title={<Fragment>{cell.helptext}</Fragment>} >
                                          <IconButton style={{ padding: 0, margin: 0, fontSize: '1rem', marginTop: -3, marginRight: 1 }}>
                                              <HelpIcon fontSize="inherit"/>
                                          </IconButton>
                                      </Tooltip>
                                      {cell.helptext.substring(0,cell.rules.sm==12?56:(cell.rules.sm==6?24:12))}
                                  </FormHelperText>
                              )}
                          </Fragment>
                        )}
                    </Fragment>
                  )

                })}
              </Grid>
              <Grid item xs={12} sx={{mb: 2}}>
                <Button size='small' type='submit' sx={{ mr: 2 }} variant='contained' onClick={handleSubmitData} disabled={searchData == null ? true : false}>
                  {reportData['搜索区域']['搜索按钮']}
                </Button>
              </Grid>

              {isLoading == false && (
                <Fragment>
                  <TableContainer sx={{ maxHeight: 800 }}>
                    <Table 
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
                          {reportData['数据区域']['头部'][0].map((cell: any, index: number) => (
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
                                px: 1,
                                py: 2,
                              }}
                            >
                              {cell.name}
                            </TableCell>
                          ))}
                        </TableRow>
                        <TableRow>
                          {reportData['数据区域']['头部'][1].map((cell: any, index: number) => (
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
                                top: 41,
                                px: 1,
                                py: 2,
                              }}
                            >
                              {cell.name}
                            </TableCell>
                          ))}
                        </TableRow>
                      </TableHead>
    
                      {/* Table body */}
                      <TableBody>
                        {reportData['数据区域']['数据'].map((cell: any, rowIndex: number) => (
                          <TableRow key={`row-${rowIndex}`}>
                            {Object.keys(cell).map((key, cellIndex) => (
                              <TableCell
                                key={`cell-${rowIndex}-${cellIndex}`}
                                rowSpan={cell.row}
                                colSpan={cell.col}
                                sx={{                                
                                  whiteSpace: cell.wrap == 'No' ? 'nowrap' : 'pre-line',
                                  wordBreak: cell.wrap == 'Yes' ? 'break-word' : 'normal',
                                  textAlign: cell.align == 'Center' ? 'center' : 'left',
                                  mx: 0,
                                  my: 1,
                                  py: 0,
                                  px: 1
                                }}
                              >
                                {cell[key]}
                              </TableCell>
                            ))}
                          </TableRow>
                        ))}
                      </TableBody>
                    </Table>
    
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
                  </TableContainer>
                  <Grid container justifyContent="flex-end" sx={{mt: 3}}>
                    {reportData['底部区域'] && reportData['底部区域']['功能按钮'] && reportData['底部区域']['功能按钮'].includes('打印') && (
                      <Button onClick={()=>{window.print();}}  variant='outlined' size="small">打印</Button>
                    )}
                  </Grid>
                </Fragment>
              )}

              {isLoading == true && (
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

export default ReportCore
