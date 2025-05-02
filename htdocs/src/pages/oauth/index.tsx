import bs58 from 'bs58'

// ** React Imports
import { useState, ReactNode, Fragment, useEffect } from 'react'

// ** MUI Components
import Button from '@mui/material/Button'
import Checkbox from '@mui/material/Checkbox'
import TextField from '@mui/material/TextField'
import InputLabel from '@mui/material/InputLabel'
import IconButton from '@mui/material/IconButton'
import Box, { BoxProps } from '@mui/material/Box'
import Grid from '@mui/material/Grid'
import useMediaQuery from '@mui/material/useMediaQuery'
import OutlinedInput from '@mui/material/OutlinedInput'
import { styled, useTheme } from '@mui/material/styles'
import FormHelperText from '@mui/material/FormHelperText'
import InputAdornment from '@mui/material/InputAdornment'
import Typography, { TypographyProps } from '@mui/material/Typography'
import MuiFormControlLabel, { FormControlLabelProps } from '@mui/material/FormControlLabel'

import CircularProgress from '@mui/material/CircularProgress'
import BlankLayout from 'src/@core/layouts/BlankLayout'
import AuthPage from 'src/views/pages/oauth/auth'
import LoginPage from 'src/views/pages/oauth/login'

import { authConfig, defaultConfig } from 'src/configs/auth'
import { DecryptDataAES256GCM } from 'src/configs/functions'

import axios from 'axios'


const OAuthPage = () => {
  const [pageModel, setPageModel] = useState<string>('Loading')

  const handleRefreshToken = () => {
    const token = window.localStorage.getItem(defaultConfig.storageTokenKeyName)
    if(window && token && authConfig)  {
      axios
        .post(authConfig.refreshEndpoint, {}, { headers: { Authorization: token, 'Content-Type': 'application/json'} })
        .then(async (response: any) => {

          let dataJson: any = null
          const data = response.data
          if(data && data.isEncrypted == "1" && data.data)  {
              const AccessKey = window.localStorage.getItem(defaultConfig.storageAccessKeyName)!
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

          if(dataJson.status == 'ok' && dataJson.accessToken) {

            //认证成功
            setPageModel('Auth')
          }

        })
    }
  }

  useEffect(() => {
    handleRefreshToken()
  },[])

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
        <LoginPage />
      )}
      {pageModel == "Auth" && (
        <AuthPage />
      )}
    </Fragment>
  )
}

OAuthPage.getLayout = (page: ReactNode) => <BlankLayout>{page}</BlankLayout>

OAuthPage.AuthAndGuestGuard = true

export default OAuthPage
