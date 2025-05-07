import { ReactNode } from 'react'

// ** MUI Components
import Button from '@mui/material/Button'
import Box from '@mui/material/Box'
import Typography from '@mui/material/Typography'
import Avatar from '@mui/material/Avatar'
import Stack from '@mui/material/Stack'
import Card from '@mui/material/Card'
import CardContent from '@mui/material/CardContent'
import Divider from '@mui/material/Divider'
import CheckCircleIcon from "@mui/icons-material/CheckCircle"

import BlankLayout from 'src/@core/layouts/BlankLayout'

import { authConfig, defaultConfig } from 'src/configs/auth'

import axios from 'axios'
import { useRouter } from 'next/router'

const AuthPage = ({ clientInfo, userData, query } : any) => {
  
  const router = useRouter()

  console.log("clientInfo", clientInfo)

  const handleAuthorizationAgree = async () => {
    const token = window.localStorage.getItem(defaultConfig.storageTokenKeyName)
    if(window && token && authConfig)  {
      try{
        await axios
          .post(authConfig.authorizationEndpoint + '?response_type=' + query.response_type + '&client_id=' + query.client_id + '&redirect_uri=' + query.redirect_uri + '&state=' + query.state , { authorized: 'Yes' },  { headers: { Authorization: token, 'Content-Type': 'application/json'} })
          .then(async (response: any) => {
  
            const data = response.data
            console.log("handleAuthorizationAgree response", data)
            if(data && data.statusCode == 302 && data.Location) {
              router.push(data.Location)
            }
  
          })
      }
      catch(Error: any) {
          console.log("handleAuthorizationAgree Error", Error)
      }
    }
  }

  const handleAuthorizationDisagree = async () => {
    const token = window.localStorage.getItem(defaultConfig.storageTokenKeyName)
    if(window && token && authConfig)  {
      router.push(clientInfo.应用URL)
    }
  }
  
  return (
    <Box
      sx={{
        p: 12,
        height: '100%',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center'
      }}
    >
      <Box
        display="flex"
        flexDirection="column"
        alignItems="center"
        justifyContent="center"
        px={2}
      >
        <Stack direction="row" spacing={2} alignItems="center" mb={3}>
          <Avatar sx={{ width: 62, height: 62 }} src={authConfig.backEndApiHost + clientInfo.应用LOGO} />
          <Box sx={{ borderTop: '2px dashed grey', width: '28px', mx: 0 }}/>
          <CheckCircleIcon sx={{ color: "green", fontSize: 28 }} />
          <Box sx={{ borderTop: '2px dashed grey', width: '28px', mx: 0 }}/>
          <Avatar sx={{ width: 62, height: 62 }} src={authConfig.logoUrl} />
        </Stack>
        <Typography variant="h5" gutterBottom>
          <Typography component="span" gutterBottom variant="subtitle1" sx={{ fontSize: '0.8em', verticalAlign: 'top', mr: 1 }}>授权</Typography>
          {' '}
          {clientInfo.应用名称}
        </Typography>


        <Card sx={{ maxWidth: 500, width: "100%", mt: 2 }}>
          <CardContent>
            <Box display="flex" alignItems="center" mb={2}>
              <Avatar sx={{ width: 48, height: 48 }} src={authConfig.backEndApiHost + clientInfo.应用LOGO} />
              <Typography variant="subtitle1" ml={2}>
                <strong>{userData.USER_NAME}</strong> 想要访问您的账户
              </Typography>
            </Box>

            <Divider sx={{ my: 2 }} />

            <Typography variant="subtitle2" gutterBottom>
              访问您的以下个人资料:
            </Typography>
            <Typography variant="body2">用户名, 用户姓名, 部门或班级信息</Typography>

          </CardContent>
          <Box display="flex" justifyContent="space-between" px={3} pb={2}>
            <Button variant="outlined" size='small' onClick={() => handleAuthorizationDisagree()} > 拒绝授权 </Button>
            <Button variant="contained" onClick={() => handleAuthorizationAgree()} > 授权访问 </Button>
          </Box>

          <Stack 
            direction="column"  // 垂直排列
            spacing={0.5}      // 上下间距
            px={2} 
            pb={2}
            pt={2}
            alignItems="center"    // 水平居中（针对子项）
            justifyContent="center" // 垂直居中（针对容器自身）
            sx={{ 
              height: "100%",      // 确保容器有高度（如需垂直居中）
              textAlign: "center"   // 文本居中（可选）
            }}
          >
            <Typography variant="caption">授权访问同意以后, 将会跳转到:</Typography>
            <Typography variant="caption" fontWeight="bold">{clientInfo.应用URL}</Typography>
          </Stack>

        </Card>

        <Typography variant="caption" mt={4}> {authConfig.AppName} 为您提供身份认证服务 </Typography>

      </Box>
    </Box>
  )
}

AuthPage.getLayout = (page: ReactNode) => <BlankLayout>{page}</BlankLayout>

export default AuthPage
