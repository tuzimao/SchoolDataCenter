
import { useState, ReactNode, Fragment, useEffect } from 'react'

// ** MUI Components
import Box from '@mui/material/Box'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'

import CircularProgress from '@mui/material/CircularProgress'
import BlankLayout from 'src/@core/layouts/BlankLayout'
import LoginPage from 'src/views/pages/oauth/login'

import { authConfig, defaultConfig } from 'src/configs/auth'
import { DecryptDataAES256GCM } from 'src/configs/functions'

import axios from 'axios'
import { useRouter } from 'next/router'

const CasPage = () => {
  const router = useRouter()
  const [pageModel, setPageModel] = useState<string>('Loading')
  const [query, setQuery] = useState<any>(null)
  
  const handleRefreshToken = () => {
    const token = window.localStorage.getItem(defaultConfig.storageTokenKeyName)
    if(window && token == null && authConfig && router && router.query && router.query.service)  {
      setPageModel("Login")
    }
    if(window && token && authConfig && router && router.query && router.query.service)  {
      axios
        .get(authConfig.refreshEndpoint + '&service=' + router.query.service, { headers: { Authorization: token, 'Content-Type': 'application/json'} })
        .then(async (response: any) => {

          let dataJson: any = null
          const data = response.data
          if(data && data.isEncrypted == "1" && data.data && data.AccessKey)  {
              const AccessKey = data.AccessKey
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
          console.log("dataJson", dataJson)

          //认证成功
          if(dataJson.status == 'ok' && dataJson.accessToken && dataJson.ClientInfo && dataJson.ClientInfo.Type && dataJson.ClientInfo.Type == 'CAS' && dataJson.ClientInfo.Ticket && dataJson.ClientInfo.Ticket != '' && router.query && router.query.service) {
            const RedirectURL = router.query.service + "?ticket=" + dataJson.ClientInfo.Ticket
            console.log("RedirectURL", RedirectURL)
            router.push(RedirectURL)
          }

          //service 不对
          if(dataJson.status == 'ok' && dataJson.accessToken && dataJson.ClientInfo == false) {
            console.log("router", router.query)
            setQuery(router.query)
            setPageModel('Error')
          }

        })
    }
  }

  useEffect(() => {
    handleRefreshToken()
  },[router, pageModel])

  return (
    <Fragment>
      {pageModel == "Loading" && (
        <Grid item xs={12} sm={12} container justifyContent="space-around">
          <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
              <CircularProgress size={45} />
              <Typography sx={{ mt: 6, mb: 6 }}>加载中...</Typography>
          </Box>
        </Grid>
      )}
      {pageModel == "Login" && (
        <LoginPage setPageModel={setPageModel} />
      )}
      {pageModel == "Error" && query && (
        <Grid item xs={12} sm={12} container justifyContent="space-around">
          <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
              <Typography sx={{ mt: 6 }}>以下信息错误:</Typography>
              <Typography sx={{ mt: 6 }}>service: {query.service}</Typography>
          </Box>
        </Grid>
      )}
    </Fragment>
  )
}

CasPage.getLayout = (page: ReactNode) => <BlankLayout>{page}</BlankLayout>

CasPage.authAndGuestGuard = true

export default CasPage
