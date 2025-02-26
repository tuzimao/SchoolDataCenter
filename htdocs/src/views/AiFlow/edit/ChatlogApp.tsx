// ** React Imports
import { useState, useEffect, Fragment } from 'react'

// ** Axios Imports
import axios from 'axios'
import { authConfig } from 'src/configs/auth'

// ** MUI Imports
import Card from '@mui/material/Card'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import { DataGrid, GridColDef } from '@mui/x-data-grid'

// ** Next Import
import { useRouter } from 'next/router'
import { useAuth } from 'src/hooks/useAuth'

// ** Third Party Import
import { useTranslation } from 'react-i18next'
import { isMobile } from 'src/configs/functions'
import { CheckPermission } from 'src/functions/ChatBook'
import { formatTimestamp } from 'src/configs/functions'
import { defaultConfig } from 'src/configs/auth'

const ChatlogApp = (props: any) => {
  // ** Hook
  const { t } = useTranslation()
  const auth = useAuth()
  const router = useRouter()
  const { appId } = props

  useEffect(() => {
    CheckPermission(auth, router, false)
  }, [])

  //const [pageData, setPageData] = useState<any>({name: '', maxToken: 16000, returnReference: 0, ipLimitPerMinute: 100, expiredTime: '', authCheck: '', appId: appId, FormAction: 'addpublish', FormTitle: 'Create', FormSubmit: 'Add', FormTitleIcon: '/images/agent/modal/shareFill.svg', openEdit: false, openDelete: false })

  const isMobileData = isMobile()
  
  // ** State
  const [isLoading, setIsLoading] = useState(false);
  const [paginationModel, setPaginationModel] = useState({ page: 0, pageSize: 15 })
  const [store, setStore] = useState<any>(null);
  console.log("setPaginationModel", setPaginationModel)

  useEffect(() => {
    fetchData(paginationModel)
    setIsLoading(false)
    console.log("router", router)
  }, [paginationModel, isMobileData, auth, appId])

  const fetchData = async function (paginationModel: any) {
    if (auth && auth.user && appId) {
      const authorization = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
      const RS = await axios.get(authConfig.backEndApiAiBaseUrl + '/api/chatlogstaticbyapp/' + appId + '/' + paginationModel.page + '/' + paginationModel.pageSize, { headers: { Authorization: authorization, 'Content-Type': 'application/json' }, params: { } }).then(res=>res.data)
      console.log("RS", RS, "appId", appId)
      setStore(RS)  
    }
  }

  //useEffect(() => {
  //  console.log("pageData", pageData)
  //}, [pageData])

  const columns: GridColDef[] = [
    {
      flex: 1,
      minWidth: 50,
      field: 'publishId',
      headerName: `${t(`Name`)}`,
      sortable: false,
      filterable: false,
      renderCell: ({ row }: any) => {
        
        return (
          <Typography noWrap variant='body2' >
            {row.publishName}
          </Typography>
        )
      }
    },
    {
      flex: 1,
      minWidth: 100,
      field: 'userId',
      headerName: `${t(`userId`)}`,
      sortable: false,
      filterable: false,
      renderCell: ({ row }: any) => {
        
        return (
          <Typography noWrap variant='body2' >
            {row.userId}
          </Typography>
        )
      }
    },
    {
      flex: 0.8,
      minWidth: 100,
      field: 'chatCount',
      headerName: `${t(`chatCount`)}`,
      sortable: false,
      filterable: false,
      renderCell: ({ row }: any) => {
        return (
          <Typography noWrap variant='body2' >
            {row.chatCount}
          </Typography>
        )
      }
    },
    {
      flex: 0.8,
      minWidth: 100,
      field: 'timestamp',
      headerName: `${t(`Date`)}`,
      sortable: false,
      filterable: false,
      renderCell: ({ row }: any) => {
        return (
          <Typography noWrap variant='body2' >
            {formatTimestamp(row.timestamp)}
          </Typography>
        )
      }
    }
  ]

  return (
    <Fragment>
      {auth.user && auth.user.email ?
      <Grid container>
      {store && store.data != undefined ?
        <Grid item xs={12}>
          <Card>
            <Grid container>
                <Grid item xs={12} lg={12} sx={{ display: 'flex', justifyContent: 'space-between' }}>
                    <Typography sx={{ my: 3, ml: 5 }}>{t('Chatlog')}</Typography>
                </Grid>
                <DataGrid
                    autoHeight
                    rows={store.data}
                    rowCount={store.total as number}
                    columns={columns}
                    sortingMode='server'
                    paginationMode='server'
                    filterMode="server"
                    loading={isLoading}
                    rowsPerPageOptions={[10, 15, 20, 30, 50, 100]}
                    page={paginationModel.page} // 使用 page 替代 paginationModel
                    pageSize={paginationModel.pageSize} // 使用 pageSize 替代 paginationModel
                />
            </Grid>
          </Card>
        </Grid>
        :
        <Fragment></Fragment>
      }
      </Grid>
      :
      null
      }
    </Fragment>
  )
}

export default ChatlogApp

