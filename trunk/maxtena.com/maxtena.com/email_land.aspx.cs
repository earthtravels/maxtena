using System;
using System.Net;
using System.Net.Mail;
using System.Web.UI;

namespace maxtena.com
{
    public partial class email_land : Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
                string emailAddress = Request.Params["email"] ?? "No email specified";
                string name = Request.Params["name"] ?? "No name specified"; ;
                string messageString = Request.Params["message"] ?? "No message specified"; ;
                MailMessage message = new MailMessage();
                message.From = new MailAddress("website@maxtena.com");

                message.To.Add(new MailAddress("vanja.maric@maxtena.com"));
                message.Subject = "Website Contact";
                message.Body = "Email Address: " + emailAddress + Environment.NewLine;
                message.Body += "Name: " + name + Environment.NewLine;
                message.Body += "Message: " + messageString + Environment.NewLine;
                SmtpClient smtp = new SmtpClient("relay-hosting.secureserver.net");
                smtp.Port = 25;
                smtp.EnableSsl = false;
                smtp.Send(message);
            }
            catch
            {
            }
            finally
            {
                Response.Redirect("contacts.htm");
            }
        }
    }
}
