import crypto from 'crypto';

export function GetIV() {
    const iv = crypto.randomBytes(16);

    return iv;
}

export function isMobile(): boolean {
  if (typeof window !== 'undefined') {
    const screenWidth = window.innerWidth;
    const userAgent = window.navigator.userAgent;
    if (screenWidth < 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent)) {
      
      return true;
    }
  }
  
  return false;
}

export function windowWidth(): number {
  if (typeof window !== 'undefined') {
    const screenWidth = window.innerWidth;
    
    return screenWidth;
  }
  
  return -1;
}

export function EncryptDataAES256GCM(text: string, key: string) {
  const iv = GetIV();
  const cipher = crypto.createCipheriv('aes-256-gcm', key, iv);
  let encrypted = cipher.update(text, 'utf-8', 'hex');
  encrypted += cipher.final('hex');
  const tag = cipher.getAuthTag();

  return { iv: iv.toString('hex'), encrypted, tag: tag.toString('hex') };
}

export function DecryptDataAES256GCM(encrypted: string, iv: string, tag: string, key: string) {
  const decipher = crypto.createDecipheriv('aes-256-gcm', key, Buffer.from(iv, 'hex'));
  decipher.setAuthTag(Buffer.from(tag, 'hex'));
  let decrypted = decipher.update(encrypted, 'hex', 'utf-8');
  decrypted += decipher.final('utf-8');

  return decrypted;
}



export function formatTimestampMemo(timestamp: number): string {
  const date = new Date(timestamp * 1000);
  const currentDate = new Date();
  const timeDifference = (currentDate.getTime() - date.getTime()) / 1000;
  if(timestamp == undefined) return ""
  let timeMemo = '';
  if (timeDifference < 60) {
    timeMemo =  ` (${Math.floor(timeDifference)} seconds)`;
  } else if (timeDifference < 3600) {
    const minutes = Math.floor(timeDifference / 60);
    timeMemo =  ` (${minutes} minute${minutes > 1 ? "s" : ""})`;
  } else if (timeDifference < 86400) {
    const hours = Math.floor(timeDifference / 3600);
    timeMemo =  ` (about ${hours} hour${hours > 1 ? "s" : ""})`;
  } else {
    const days = Math.floor(timeDifference / 86400);
    timeMemo =  ` (about ${days} day${days > 1 ? "s" : ""})`;
  }

  const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  const month = months[date.getMonth()];
  const day = date.getDate();
  const year = date.getFullYear();
  const hours = date.getHours();
  const minutes = date.getMinutes();
  const seconds = date.getSeconds();
  const ampm = hours >= 12 ? "PM" : "AM";
  const formattedDate = `${month} ${day}, ${year} ${hours}:${minutes}:${seconds} ${ampm} ${timeMemo}`;

  return formattedDate;
}

export function formatTimestampAge(timestamp: number): string {
  const date = new Date(timestamp * 1000);
  const currentDate = new Date();
  const timeDifference = (currentDate.getTime() - date.getTime()) / 1000;
  if(timestamp == undefined) return ""
  let timeMemo = '';
  if (timeDifference < 60) {
    timeMemo =  `${Math.floor(timeDifference)} seconds`;
  } else if (timeDifference < 3600) {
    const minutes = Math.floor(timeDifference / 60);
    timeMemo =  `${minutes} minute${minutes > 1 ? "s" : ""}`;
  } else if (timeDifference < 86400) {
    const hours = Math.floor(timeDifference / 3600);
    timeMemo =  `about ${hours} hour${hours > 1 ? "s" : ""}`;
  } else {
    const days = Math.floor(timeDifference / 86400);
    timeMemo =  `about ${days} day${days > 1 ? "s" : ""}`;
  }
  
  return timeMemo;
}

export function formatTimestamp(timestamp: number): string {
  const date = new Date(timestamp);
  if(timestamp == undefined) return ""
  const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  const month = months[date.getMonth()];
  const day = date.getDate();
  const year = date.getFullYear();
  const hours = date.getHours();
  const minutes = date.getMinutes();
  const seconds = date.getSeconds();
  const ampm = hours >= 12 ? "PM" : "AM";
  const formattedDate = `${month} ${day}, ${year} ${hours}:${minutes}:${seconds} ${ampm}`;

  return formattedDate;
}
