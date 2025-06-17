// ** React Imports
import { useState, useEffect, Fragment } from 'react'

// ** MUI Imports
import Typography from '@mui/material/Typography'
import Box from '@mui/material/Box'
import Table from '@mui/material/Table'
import TableRow from '@mui/material/TableRow'
import TableBody from '@mui/material/TableBody'
import TableHead from '@mui/material/TableHead'
import TableContainer from '@mui/material/TableContainer'
import TableCell from '@mui/material/TableCell'
import Grid from '@mui/material/Grid'
import CircularProgress from '@mui/material/CircularProgress'
import { useTheme } from '@mui/material/styles'

// ** Config
import { defaultConfig } from 'src/configs/auth'
import axios from 'axios'

// ** Styles
import 'react-draft-wysiwyg/dist/react-draft-wysiwyg.css'

import { DecryptDataAES256GCM } from 'src/configs/functions'

const ViewTableList = ({ authConfig, backEndApi, currentReport, xName, yValue}: any) => {

  const [isLoading, setIsLoading] = useState(false);
  const [tableListData, setTableListData] = useState<any>(null)
  const theme = useTheme()
  const borderColor = theme.palette.mode === 'dark' ? theme.palette.grey[600] : theme.palette.grey[300]

  const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
  const AccessKey = window.localStorage.getItem(defaultConfig.storageAccessKeyName)!

  useEffect(() => {
    if (backEndApi && backEndApi.length > 0) {
      setIsLoading(true)
      setTableListData(null)
      axios
        .post(
          authConfig.backEndApiHost + backEndApi + '?action=report_detail&currentReport=' + currentReport,
          {报表横向字段: xName, 报表纵向字段值: yValue},
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
            setTableListData(dataJson)
          }
          setIsLoading(false)
        })
        .catch(() => {
          setIsLoading(false)
          console.log("axios.get editUrl return")
        })
    }
  }, [])
  
  return (
    <Fragment>
      {isLoading == false && tableListData && (
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
                  {tableListData && tableListData['data'] && tableListData['data'][0] && Object.keys(tableListData['data'][0]).map((cell: any, index: number) => (
                    <TableCell
                      key={`header1-${index}`}
                      rowSpan={cell.row}
                      colSpan={cell.col}
                      sx={{                                
                        whiteSpace: 'nowrap',
                        wordBreak: 'break-word',
                        textAlign: 'Center',
                        fontWeight: 'bold',
                        position: 'sticky',
                        top: 0,
                        mx: '0 !important',
                        my: '0 !important',
                        py: '4px !important',
                        px: '8px !important',
                      }}
                    >
                      {cell}
                    </TableCell>
                  ))}
                </TableRow>
              </TableHead>

              {/* Table body */}
              <TableBody>
                {tableListData && tableListData['data'] && tableListData['data'].map((cell: any, rowIndex: number) => (
                  <TableRow key={`row-${rowIndex}`} >
                    {Object.keys(cell).map((key, cellIndex) => (
                      <TableCell
                        key={`cell-${rowIndex}-${cellIndex}`}
                        sx={{                                
                          whiteSpace: 'nowrap',
                          wordBreak: 'break-word',
                          textAlign: 'Center',
                          mx: '0 !important',
                          my: '0 !important',
                          py: '6px !important',
                          px: '0 !important'
                        }}
                      >
                        {cell[key]}
                      </TableCell>
                    ))}
                  </TableRow>
                ))}
                {tableListData && tableListData['data'] && tableListData['data'] == null && (
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
          </TableContainer>
        </Fragment>
      )}
      {tableListData == null && isLoading == true && (
        <Grid item xs={12} sm={12} container justifyContent="space-around">
          <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
              <CircularProgress />
              <Typography sx={{pt:5, pb:5}}>正在加载中</Typography>
          </Box>
        </Grid>
      )}
    </Fragment>
  );
};

export default ViewTableList
