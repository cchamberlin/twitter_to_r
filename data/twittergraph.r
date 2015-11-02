#How to get data into R and graph it

test.csv <- read.csv("C:/xampp/htdocs/php/twitter_to_r/data/testdata.csv", header=T)
print(test.csv)
x<-test.csv$Date
y<-test.csv$AvgSentiment
x <- as.Date(x, format="%m/%d/%Y")
png("C:/xampp/htdocs/php/twitter_to_r/sentiment.png")
plot(x,y, type="o", pch=23, col="darkgreen", bg="darkgreen", ylim=c(0,1), xlab="", ylab="Avg Sentiment", xaxt="n")
axis(1, x, format(x, "%D"))
dev.off()

# Ugly, right?