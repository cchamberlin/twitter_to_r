# How to get data into R and graph it

# con <- dbConnect(MySQL(), user='root', dbname='twittersentiment',host='localhost')
# rs <- dbSendQuery(con, "SELECT RunDate, Sentiment FROM TestRuns WHERE Hashtag = '#MNVikings';")
# data <- fetch(rs, n=100)
# huh <- dbHasCompleted(rs)
# dbClearResult(rs)
# dbDisconnect(con)

# Libraries
library(DBI)
library(RMySQL)


# Get command line arguments
args <- commandArgs(TRUE)
thisDate <- as.Date(args[1],format="%m/%d/%Y")
thisScore <- args[2]
thisTag <- args[3]

# Build query
thisQuery <- paste("SELECT RunDate, Sentiment FROM TestRuns WHERE Hashtag = '", thisTag, "';", sep="")

thisQuery

# Get historical data
# test.csv <- read.csv("C:/xampp/htdocs/php/twitter_to_r/data/testdata.csv", header=T)
con <- dbConnect(MySQL(), user='root', dbname='twittersentiment',host='localhost')
rs <- dbSendQuery(con, thisQuery)
data <- fetch(rs, n=100)
huh <- dbHasCompleted(rs)
dbClearResult(rs)
dbDisconnect(con)

# Debug Printing
#print(test.csv)

# Set up vectors
x<-data$RunDate
x<-as.Date(x, format="%Y-%m-%d")
y<-data$Sentiment
x <- c(x, thisDate)
y <- c(y, thisScore)

# Build the graph
png("C:/xampp/htdocs/php/twitter_to_r/sentiment.png")
plot(x,y, type="o", pch=23, col="darkgreen", bg="darkgreen", ylim=c(-1,1), xlab="", ylab="Avg Sentiment", xaxt="n")
axis(1, x, format(x, "%D"))

# Interactive debug only
#dev.off() 
# Ugly, right?

