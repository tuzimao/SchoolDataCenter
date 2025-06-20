import bs58 from 'bs58'

// ** React Imports
import { useState, ReactNode, Fragment } from 'react'

// ** MUI Components
import Button from '@mui/material/Button'
import Checkbox from '@mui/material/Checkbox'
import TextField from '@mui/material/TextField'
import InputLabel from '@mui/material/InputLabel'
import IconButton from '@mui/material/IconButton'
import Box, { BoxProps } from '@mui/material/Box'
import FormControl from '@mui/material/FormControl'
import useMediaQuery from '@mui/material/useMediaQuery'
import OutlinedInput from '@mui/material/OutlinedInput'
import { styled, useTheme } from '@mui/material/styles'
import FormHelperText from '@mui/material/FormHelperText'
import InputAdornment from '@mui/material/InputAdornment'
import Typography, { TypographyProps } from '@mui/material/Typography'
import MuiFormControlLabel, { FormControlLabelProps } from '@mui/material/FormControlLabel'

import { DecryptDataAES256GCM } from 'src/configs/functions'

// ** Icon Imports
import Icon from 'src/@core/components/icon'
import Link from 'next/link'
import Avatar from '@mui/material/Avatar'

// ** Third Party Imports
import * as yup from 'yup'
import { useForm, Controller } from 'react-hook-form'
import { yupResolver } from '@hookform/resolvers/yup'

import { setLocale } from 'yup';
import AddOrEditTableLanguage from 'src/types/forms/AddOrEditTableLanguage';

setLocale(AddOrEditTableLanguage);

// ** Hooks
import axios from 'axios'
import { useAuth } from 'src/hooks/useAuth'
import { useSettings } from 'src/@core/hooks/useSettings'

// ** Configs
import themeConfig from 'src/configs/themeConfig'

// ** Layout Import
import BlankLayout from 'src/@core/layouts/BlankLayout'

import { authConfig } from 'src/configs/auth'

const LinkStyled = styled(Link)(({ theme }) => ({
  fontSize: '0.875rem',
  textDecoration: 'none',
  color: theme.palette.primary.main
}))

const LoginIllustration = styled('img')(({ theme }) => ({
  maxWidth: '95%',
  borderRadius: '10px', // 增加圆角
  opacity: 0.9, // 设置透明度为90%
  [theme.breakpoints.down('lg')]: {
    maxWidth: '95%'
  }
}));


const RightWrapper = styled(Box)<BoxProps>(({ theme }) => ({
  width: '100%',
  [theme.breakpoints.up('md')]: {
    maxWidth: 450
  }
}))

const BoxWrapper = styled(Box)<BoxProps>(({ theme }) => ({
  [theme.breakpoints.down('xl')]: {
    width: '100%'
  },
  [theme.breakpoints.down('md')]: {
    maxWidth: 400
  }
}))

const TypographyStyled = styled(Typography)<TypographyProps>(({ theme }) => ({
  fontWeight: 600,
  marginBottom: theme.spacing(1.5),
  [theme.breakpoints.down('md')]: { mt: theme.spacing(8) }
}))

const FormControlLabel = styled(MuiFormControlLabel)<FormControlLabelProps>(({ theme }) => ({
  '& .MuiFormControlLabel-label': {
    fontSize: '0.875rem',
    color: theme.palette.text.secondary
  }
}))

const schema = yup.object().shape({
  username: yup.string().min(3).required().label('用户名'),
  password: yup.string().min(6).required().label('密码'),
  termsofUse: yup.boolean().required('请同意使用协议').test('is-true', '请同意使用协议', value => value === true)
})

const defaultValues = {
  password: '',
  username: '',
  termsofUse: false
}

interface FormData {
  username: string
  password: string
  termsofUse: boolean
}

const LoginPage = ({ setPageModel }: any) => {
  const [showPassword, setShowPassword] = useState<boolean>(false)

  // ** Hooks
  const auth = useAuth()
  const theme = useTheme()
  const { settings } = useSettings()
  const hidden = useMediaQuery(theme.breakpoints.down('md'))
  const [loginButtonText, setLoginButtonText] = useState<string>("登录");
  const [loginButtonDisabled, setLoginButtonDisabled] = useState<boolean>(false);

  // ** Vars
  const { skin } = settings

  const {
    control,
    setError,
    handleSubmit,
    formState: { errors }
  } = useForm({
    defaultValues,
    mode: 'onBlur',
    resolver: yupResolver(schema)
  })

  function sha256(str: string): Promise<string> {
    const encoder = new TextEncoder();
    const data = encoder.encode(str);

    return crypto.subtle.digest('SHA-256', data).then(buf => {
      return Array.from(new Uint8Array(buf))
        .map(b => b.toString(16).padStart(2, '0'))
        .join('');
    });
  }

  function base58Encode(data: string) {
    const bytes = Buffer.from(data, 'utf8');
    const encoded = bs58.encode(bytes);

    return encoded;
  }

  const onSubmit = async (data: FormData) => {
    const { username, password } = data
    
    try {
      const res = await axios.get(authConfig.powEndpoint);
      let dataJson: any = null
      const data = res.data
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
              console.log("DecryptDataAES256GCMData authConfig.powEndpoint Error", Error)
              dataJson = data
          }
      }
      else {
          dataJson = data
      }
      const challenge = dataJson.challenge;
      const difficulty = dataJson.difficulty;
      const loadingButtonText = dataJson.loadingButtonText;
      let nonce = 0;
      let hash = '';

      setLoginButtonDisabled(true)
      setLoginButtonText(loadingButtonText);
    
      while (true) {
        const test = challenge + nonce.toString();
        hash = await sha256(test);
        if (hash.startsWith(difficulty)) {
          break;
        }
        nonce++;
      }
      console.log("Challenge string:", challenge)
      console.log("Challenge Result:", hash)

      auth.login({Data: base58Encode(base58Encode(JSON.stringify({ username, password, rememberMe: true, challenge, hash, nonce})))}
        , () => {

          //登录失败
          setError('username', {
            type: 'manual',
            message: '用户名或密码错误'
          })
        }
        , () => {

          //登录成功, 不能注释, 如果注释就是常规的登录操作
          setPageModel('Auth')
        }
      )
      setLoginButtonText('登录');

    }
    catch(Error: any) {
      console.log("Login onSubmit error:", Error)
    }

  }

  return (
    <Box className='content-right'>
      {!hidden && (
        <Box sx={{ flex: 1, display: 'flex', position: 'relative', alignItems: 'center', justifyContent: 'center' }}>
            <LoginIllustration
              src={authConfig.indexImageUrl}
            />
        </Box>
      )}
      <RightWrapper sx={skin === 'bordered' && !hidden ? { borderLeft: `1px solid ${theme.palette.divider}` } : {}}>
        <Box
          sx={{
            p: 12,
            height: '100%',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            backgroundColor: 'background.paper'
          }}
        >
          <BoxWrapper>
            <Box
              sx={{
                top: 30,
                left: 40,
                display: 'flex',
                position: 'absolute',
                alignItems: 'center',
                justifyContent: 'center'
              }}
            >
              <Avatar src={authConfig.logoUrl} sx={{ width: '2.5rem', height: '2.5rem' }} />
              <Typography
                variant='h6'
                sx={{
                  ml: 2,
                  lineHeight: 1,
                  fontWeight: 600,
                  fontSize: '1.5rem !important'
                }}
              >
                {themeConfig.templateName}
              </Typography>
            </Box>
            <Box sx={{ mb: 6 }}>
              <TypographyStyled variant='h5'>欢迎来到 {themeConfig.templateName}! 👋🏻</TypographyStyled>
            </Box>
            <form noValidate autoComplete='off' onSubmit={handleSubmit(onSubmit)}>
              <FormControl fullWidth sx={{ mb: 4 }}>
                <Controller
                  name='username'
                  control={control}
                  rules={{ required: true }}
                  render={({ field: { value, onChange, onBlur } }) => (
                    <TextField
                      autoFocus
                      label='用户名'
                      value={value}
                      onBlur={onBlur}
                      onChange={onChange}
                      disabled={loginButtonDisabled}
                      error={Boolean(errors.username)}
                      placeholder=''
                    />
                  )}
                />
                {errors.username && <FormHelperText sx={{ color: 'error.main' }}>{errors.username.message}</FormHelperText>}
              </FormControl>
              <FormControl fullWidth>
                <InputLabel htmlFor='auth-login-v2-password' error={Boolean(errors.password)}>
                  密码
                </InputLabel>
                <Controller
                  name='password'
                  control={control}
                  rules={{ required: true }}
                  render={({ field: { value, onChange, onBlur } }) => (
                    <OutlinedInput
                      value={value}
                      onBlur={onBlur}
                      label='密码'
                      onChange={onChange}
                      disabled={loginButtonDisabled}
                      id='auth-login-v2-password'
                      error={Boolean(errors.password)}
                      type={showPassword ? 'text' : 'password'}
                      endAdornment={
                        <InputAdornment position='end'>
                          <IconButton
                            edge='end'
                            disabled={loginButtonDisabled}
                            onMouseDown={e => e.preventDefault()}
                            onClick={() => setShowPassword(!showPassword)}
                          >
                            <Icon icon={showPassword ? 'mdi:eye-outline' : 'mdi:eye-off-outline'} fontSize={20} />
                          </IconButton>
                        </InputAdornment>
                      }
                    />
                  )}
                />
                {errors.password && (
                  <FormHelperText sx={{ color: 'error.main' }} id=''>
                    {errors.password.message}
                  </FormHelperText>
                )}
              </FormControl>
              <Box sx={{ display: 'flex', alignItems: 'center', flexWrap: 'wrap', justifyContent: 'space-between' }} >
                <FormControl fullWidth sx={{ mb: 2 }}>
                  <Controller
                    name='termsofUse'
                    control={control}
                    rules={{ required: true }}
                    render={({ field: { value, onChange } }) => (
                      <FormControlLabel
                        disabled={loginButtonDisabled}
                        control={<Checkbox checked={value} onChange={onChange} />}
                        label={
                          <Fragment>
                            <span>我同意 </span>
                            <LinkStyled href='/TermsofUse' target="_blank" sx={{mx: 1}}>使用协议</LinkStyled>
                            <LinkStyled href='/PrivacyPolicy' target="_blank" sx={{mx: 1}}>隐私政策</LinkStyled>                            
                            {errors.termsofUse && <span style={{ color: '#FF4C51', marginLeft: '4px'}}>{errors.termsofUse.message} </span>}
                          </Fragment>
                        }
                      />
                    )}
                  />
                </FormControl>
              </Box>
              <Button fullWidth size='large' type='submit' variant='contained' sx={{ mb: 7 }} disabled={loginButtonDisabled}>
                {loginButtonText}
              </Button>
            </form>
          </BoxWrapper>
        </Box>
      </RightWrapper>
    </Box>
  )
}

LoginPage.getLayout = (page: ReactNode) => <BlankLayout>{page}</BlankLayout>

LoginPage.authAndGuestGuard = true

export default LoginPage
